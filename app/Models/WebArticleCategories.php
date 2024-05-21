<?php

namespace App\Models;

use App\Traits\GlobalQueryTraits;
use App\Traits\HasResponses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MathPHP\Finance;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class WebArticleCategories extends Model
{
    use GlobalQueryTraits,HasFactory, HasResponses;

    protected static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            // only api
            if (request()->is('page/*')) {
                $builder->where('status', 1);
            }
        });

        self::created(function () {
            ResponseCache::clear();
        });

        self::updated(function () {
            ResponseCache::clear();
        });

        self::deleted(function () {
            ResponseCache::clear();
        });
    }

    public $casts = [
        'loan' => SchemalessAttributes::class,
        'deposit' => SchemalessAttributes::class,
    ];

    protected $table = 'web_article_categories';

    protected $fillable = [
        'parent',
        'loan',
        'deposit',
        'image',
        'image_xs',
        'image_sm',
        'image_md',
        'image_lg',
        'sort',
        'visibility',
        'status',
        'custom',
    ];

    public function translations()
    {
        return $this->hasMany(WebArticleCategoryTranslations::class, 'category_id');
    }

    public function parents()
    {
        return $this->belongsTo(WebArticleCategories::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(WebArticleCategories::class, 'parent')->orderBy('sort', 'asc');
    }

    public function relation()
    {
        return $this->hasMany(WebArticleCategoryRelations::class, 'category_id');
    }

    public function menu_relation()
    {
        return $this->belongsTo(WebMenus::class, 'id', 'category_id');
    }

    public function email()
    {
        return $this->hasMany(WebEmail::class, 'id_category');
    }

    public function breadcrumb($id, $lang)
    {
        $language = _get_languages($lang);
        $data = $this->where('id', $id)->first();
        $result = [];
        if ($data) {
            $result[] = [
                'id' => $data->id,
                'url' => '/'.$lang.'/'.$data->translations->where('language_id', $language)->first()->slug,
                'name' => $data->translations->where('language_id', $language)->first()->name,
            ];
            if ($data->parent) {
                $result = array_merge($this->breadcrumb($data->parent, $lang), $result);
            }
        }

        return $result;
    }

    public function loan_calculator($data)
    {
        $_metode = $data->metode;
        $_loan = (int) $data->loan;
        $_tenure = (int) $data->tenure;
        $_interest_rate = (int) $data->interest_rate;
        $data->lang = _get_languages($data->lang);

        if ($_tenure > 240) {
            $_tenure = 240;
        }

        switch ($_metode) {
            case 'flat':
                $result = $this->metode_flat($_loan, $_tenure, $_interest_rate);
                break;

            case 'efektif':
                $result = $this->metode_efektif($_loan, $_tenure, $_interest_rate);
                break;

            case 'anuitas':
                $result = $this->metode_anuitas($_loan, $_tenure, $_interest_rate);
                break;

            default:
                $result = [];
                break;
        }

        return view('engine.include.calculator.loan', compact('result', 'data'))->render();
    }

    public function deposit_calculator($data)
    {
        $monthsInYear = 12;
        $_deposito = (int) $data->deposito;
        $_periode = (int) $data->periode;
        $_tax = (int) $data->tax;
        $_tax_after = 100 - $_tax;

        $gettable = DB::table('web_tenure_deposits')->get();

        $data_table = [];
        foreach ($gettable as $key => $value) {
            $temp = str_replace('[value]', $data->deposito, $value->value);
            if (eval("return ($temp);")) {
                $data_table = $value;
            }
        }

        if (empty($data_table)) {
            return '';
        }
        switch ($_periode) {
            case 1:
                $_interest_rate = $data_table->month_1;
                break;
            case 3:
                $_interest_rate = $data_table->months_3;
                break;
            case 6:
                $_interest_rate = $data_table->months_6;
                break;
            case 12:
                $_interest_rate = $data_table->months_12;
                break;
            default:
                $_interest_rate = 0;
                break;
        }

        if ($_interest_rate == 0) {
            return '';
        }

        if ($_periode > 240) {
            $_periode = 240;
        }

        $depositoAfter = $_deposito;

        $_interest_nett = 0;
        $_last_deposito = 0;
        $_last_tax = 0;
        $_last_InterestAmount = 0;

        if ($data->deposit_type == 'aro-1') {
            $result = [
                [
                    'no' => 0,
                    'deposito' => $_deposito,
                    'InterestAmount' => 0,
                    'taxAfter' => 0,
                    'InterestAmountAfter' => 0,
                    'depositoAfter' => $_deposito,
                ],
            ];
            for ($i = 0; $i < $_periode; $i++) {
                $_deposito = round($depositoAfter);
                $InterestAmount = ($_deposito * ($_interest_rate / 100) * (30 / 365) * ($_tax / 100));
                $taxAfter = ($_deposito * ($_interest_rate / 100) * (30 / 365) * ($_tax_after / 100));
                $depositoAfter = $_deposito + $taxAfter;

                $_last_tax += $taxAfter;
                $_last_deposito = $depositoAfter;

                array_push($result, [
                    'no' => $i + 1,
                    'deposito' => number_format($_deposito, 2),
                    'InterestAmount' => number_format($InterestAmount, 2),
                    'taxAfter' => number_format($taxAfter, 2),
                    'depositoAfter' => number_format($depositoAfter, 2),
                ]);
            }
            $data = [
                'interest_nett' => number_format(($_last_deposito - (int) $data->deposito), 2),
                'last_tax' => number_format($_last_tax, 2),
                'last_deposito' => number_format($_last_deposito, 2),
                'lang' => _get_languages($data->lang),
            ];
        } else {
            $result = [
                [
                    'no' => 0,
                    'deposito' => $_deposito,
                    'InterestAmount' => 0,
                    'taxAfter' => 0,
                ],
            ];
            for ($i = 0; $i < $_periode; $i++) {
                $_deposito = round($_deposito);
                $InterestAmount = ($_deposito * ($_interest_rate / 100) * (30 / 365) * ($_tax / 100));
                $taxAfter = ($_deposito * ($_interest_rate / 100) * (30 / 365) * ($_tax_after / 100));
                $depositoAfter = $_deposito + $taxAfter;

                $_last_tax += $taxAfter;
                $_last_InterestAmount += $InterestAmount;

                array_push($result, [
                    'no' => $i + 1,
                    'deposito' => number_format($_deposito, 2),
                    'InterestAmount' => number_format($InterestAmount, 2),
                    'taxAfter' => number_format($taxAfter, 2),
                ]);
            }
            $data = [
                'interest_nett' => number_format($_last_InterestAmount, 2),
                'last_tax' => number_format($_last_tax, 2),
                'lang' => _get_languages($data->lang),
            ];

        }

        return view('engine.include.calculator.deposit', compact('result', 'data'))->render();
    }

    public function metode_flat($loan, $tenure, $interest_rate)
    {
        $result = [];

        // jika interest_rate 3 digits , maka dibagi 10
        if (strlen($interest_rate) > 2) {
            $interest_rate = $interest_rate / 10;
        }

        $interest_rate = $interest_rate / 100;
        $pokok = $loan / $tenure;
        $bunga = $loan * ($interest_rate / $tenure);
        $sisaPinjaman = $loan;
        $jumlahAngsuran = $pokok + $bunga;

        for ($i = 0; $i < $tenure; $i++) {
            $sisaPinjaman -= $pokok;
            array_push($result, [
                'no' => $i + 1,
                'pokok' => number_format($pokok, 2),
                'bunga' => number_format($bunga, 2),
                'jumlahAngsuran' => number_format($jumlahAngsuran, 2),
                'sisaPinjaman' => number_format($sisaPinjaman, 2),
            ]);
        }

        return $result;
    }

    public function metode_efektif($loan, $tenure, $interest_rate)
    {
        $daysInMonth = 30;
        $daysInYear = 360;
        $monthsInYear = 12;

        $result = [];
        $interest_rate = $interest_rate / 100;
        $sisaPinjaman = $loan;
        $pokok = $loan / $tenure;

        for ($i = 0; $i < $tenure; $i++) {
            $bunga = $sisaPinjaman * $interest_rate * ($daysInMonth / $daysInYear);
            $jumlahAngsuran = ($pokok + $bunga);
            $sisaPinjaman -= $pokok;
            array_push($result, [
                'no' => $i + 1,
                'pokok' => number_format($pokok, 2),
                'bunga' => number_format($bunga, 2),
                'jumlahAngsuran' => number_format($jumlahAngsuran, 2),
                'sisaPinjaman' => number_format($sisaPinjaman, 2),
            ]);
        }

        return $result;
    }

    public function metode_anuitas($loan, $tenure, $interest_rate)
    {
        $result = [];
        $interest_rate = $interest_rate / 100;
        $jumlahAngsuran = Finance::pmt($interest_rate, $tenure, -$loan);
        $sisaPinjaman = $loan;

        for ($i = 0; $i < $tenure; $i++) {
            $pokok = Finance::ppmt(
                $interest_rate,
                ($i + 1),
                $tenure,
                -$loan
            );
            $bunga = Finance::ipmt(
                $interest_rate,
                ($i + 1),
                $tenure,
                -$loan
            );
            $sisaPinjaman -= $pokok;

            array_push($result, [
                'no' => $i + 1,
                'pokok' => number_format($pokok, 2),
                'bunga' => number_format($bunga, 2),
                'jumlahAngsuran' => number_format($jumlahAngsuran, 2),
                'sisaPinjaman' => number_format($sisaPinjaman, 2),
            ]);
        }

        return $result;
    }
}
