<?php

namespace App\Http\Controllers\Api\Chatbot;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebChatbotCategories;
use App\Models\WebHistoryChatbot;
use App\Models\WebHistoryChatbotSession;
use App\Services\LogServices;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ChatbotApiController extends BaseController
{
    public function __construct(LogServices $LogServices, Request $request)
    {
        $this->LogServices = $LogServices;
        $this->request = $request;
    }

    public function InitChat(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|string|max:255',
                'title' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric',
            ], [
                'name.required' => 'Name is required',
                'title.required' => 'Title is required',
                'email.required' => 'Email is required',
                'phone.required' => 'Phone is required',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 404);
            }
            $language = 1;
            $request->titleBygender = $request->title == 91 ? 'Mr' : 'Mrs';
            $chatText = shortCode(Helper::_wording('greetings_chatbot', $language), $request);
            $chat = WebHistoryChatbot::create([
                'json' => $this->InitChatText($chatText),

            ]);

            $session = WebHistoryChatbotSession::create([
                'name' => $request->name,
                'gender' => $request->title == 91 ? 'L' : 'P', // 'Mr' or 'Mrs
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $relation = $session->relation()->create([
                'id_chatbot' => $chat->id,
                'id_session' => $session->id,
            ]);

            $id = base64_encode(base64_encode($chat->id));
            $email = base64_encode(base64_encode($request->email));
            $id_session = base64_encode(base64_encode($session->id));
            $result = [
                'id_chatbot' => $id,
                'id_session' => $id_session,
                'email' => $email,
            ];
            DB::commit();

            return $this->sendResponse($result, 'Chatbot created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return $this->sendError('Something went wrong', [], 404);
        }

    }

    public function InitChatText($chatText)
    {
        $language = 1;
        $category = WebChatbotCategories::with(['translations', 'children.translations'])->where('parent', 0)->get();
        $category = collect($category)->filter()->map(function ($item) use ($language) {
            $item = $item->getResponeses($item, $language);
            $item = collect($item)->toArray();

            return [
                'text' => $item['name'],
                'value' => $item['slug'],
            ];
        });
        $data = [
            [
                'type' => 'choices',
                'operator' => true,
                'question' => $chatText,
                'choice' => $category,
                'date' => date('d M Y H:i:s'),
            ],
        ];

        return collect($data)->values()->toArray();
    }

    public function PostSendChat(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'text' => 'required|string',
                'id_chatbot' => 'required|string',
                'lang' => 'required|string',
            ], [
                'text.required' => 'Text is required',
                'id_chatbot.required' => 'Id Chatbot is required',
                'lang.required' => 'Language is required',
            ]);
            $languages = $request->lang;
            $language = _get_languages($languages);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 404);
            }

            $searchTerm = strtolower($request->text);
            $id_chatbot = $request->id_chatbot;
            $id_chatbot = base64_decode(base64_decode($id_chatbot));

            $chatbot = WebHistoryChatbot::find($id_chatbot);

            if (! $chatbot) {
                return $this->sendResponse([], 'Chatbot retrieved successfully.');
            }
            $article = [];
            $text = [];
            $choices = [];
            if ($chatbot->close_chat == 1) {
                // closing text
                if (in_array($searchTerm, ['no', 'nope', 'no thanks', 'no thank you', 'no, thanks', 'no, thank you', 'no, thank'])) {
                    $text = Helper::_wording('thankyou_chatbot', $language);
                    $text = $this->SendText($text);
                }

                // repeat text
                if (in_array($searchTerm, ['yes', 'yep', 'yes please', 'yes, please', 'yes, thank', 'yes, thanks', 'yes thank you', 'yes, thank you', 'yes thank', 'yes, thank', 'help', 'help me', 'help me please'])) {
                    $text = Helper::_wording('repeat_chatbot', $language);
                    $text = collect($this->InitChatText($text))->first();
                    $chatbot->close_chat = 0;
                }
            }

            if (empty($text)) {
                $name = $searchTerm;
                $category = WebChatbotCategories::with(['translations', 'children.translations'])->whereHas('translations', function ($q) use ($name) {
                    $q->whereRaw('LOWER(`name`) = ?', [strtolower(trim($name))]);
                })->first();
                if (! empty($category)) {
                    $children = $category?->children;
                    $category = $category->getResponeses($category, 1);
                    $category = collect($category)->toArray();
                    $category['overview'] = isset($category['meta']['description']) ? $category['meta']['description'] : $category['description'];

                    $children = collect($children)->filter()->map(function ($item) {
                        $item = $item->getResponeses($item, 1);
                        $item = collect($item)->toArray();

                        return [
                            'text' => $item['name'],
                            'value' => $item['slug'],
                        ];
                    });

                    if (collect($children)->count() == 0) {
                        $article = $this->SendArticle($category);
                        $closing['overview'] = Helper::_wording('closing_chatbot', $language);
                        $choices = $this->SendChoices($closing);
                        $chatbot->close_chat = 1;
                    } else {
                        $choices = $this->SendChoices($category, $children);
                    }
                } else {
                    $data = Helper::_text_chatbot($languages, $searchTerm);
                    $data = collect($data)->first();
                    if (empty($data)) {
                        $text = Helper::_wording('not_found_chatbot', $language);
                        $text = $this->SendText($text);
                    } else {
                        $article = $this->SendArticle($data);
                        $closing['overview'] = Helper::_wording('closing_chatbot', $language);
                        $choices = $this->SendChoices($closing);
                        $chatbot->close_chat = 1;
                    }
                }
            }

            $users = $this->SendText($searchTerm, false);
            $append = collect($chatbot->json)->merge([$users, $article, $choices, $text])->filter()->values()->toArray();
            $chatbot->json = $append;
            $chatbot->save();
            $result['chat'] = $this->AdjustChat($chatbot);

            return $this->sendResponse($result, 'Chatbot retrieved successfully.');
        } catch (\Exception $e) {
            return $e;
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function Getchatbyidchat($id)
    {
        $id = base64_decode(base64_decode($id));
        $chatbot = WebHistoryChatbot::find($id);

        if (! $chatbot) {
            return $this->sendResponse([], 'Chatbot retrieved successfully.');
        }

        // check session
        $session = $chatbot?->relation()?->first()?->session?->created_at;
        $diff = date_diff(date_create($session->format('Y-m-d H:i:s')), date_create(date('Y-m-d H:i:s')));
        $diff = $diff->format('%a');
        if ($diff > 1) {
            return $this->sendError('Session expired', [], 300);
        }
        $data['chat'] = $this->AdjustChat($chatbot);
        $data['is_typing'] = Helper::_wording('is_typing', 1);

        return $this->sendResponse($data, 'Chatbot retrieved successfully.');
    }

    public function GetAllChatByIdSession($id)
    {
        $id = base64_decode(base64_decode($id));
        $session = WebHistoryChatbotSession::orderBy('id', 'desc')->find($id);
        if (! $session) {
            return $this->sendResponse([], 'Session retrieved successfully.');
        }

        $data = $session->relation()->with('chatbot')->get();
        $data = collect($data)->map(function ($item) {
            $chatbot = collect($item->chatbot->json)->filter()->values()->last();
            $item = [
                'description' => isset($chatbot['question']) ? $chatbot['question'] : (isset($chatbot['description']) ? $chatbot['description'] : ''),
                'date' => isset($chatbot['date']) ? $chatbot['date'] : date('Y-m-d H:i:s'),
                'id_chat' => base64_encode(base64_encode($item->chatbot->id)),
            ];

            return $item;
        });

        return $this->sendResponse($data, 'Session retrieved successfully.');
    }

    public function GetNewSession($id)
    {
        DB::beginTransaction();
        try {
            $id_session = base64_decode(base64_decode($id));
            $session = WebHistoryChatbotSession::find($id_session);
            if (! $session) {
                return $this->sendResponse([], 'Session retrieved successfully.');
            }
            $language = 1;
            $category = WebChatbotCategories::with(['translations', 'children.translations'])->where('parent', 0)->get();
            $category = collect($category)->filter()->map(function ($item) use ($language) {
                $item = $item->getResponeses($item, $language);
                $item = collect($item)->toArray();

                return [
                    'text' => $item['name'],
                    'value' => $item['slug'],
                ];
            });

            $chat = WebHistoryChatbot::create([
                'json' => [
                    [
                        'type' => 'choices',
                        'operator' => true,
                        'question' => 'Hi, '.$session->name.'! Welcome to Bank of India. What would you like to know?',
                        'choice' => $category,
                    ],
                ],
            ]);

            $relation = $session->relation()->create([
                'id_chatbot' => $chat->id,
                'id_session' => $session->id,
            ]);
            $result['chat'] = $this->AdjustChat($chat);
            $result['id_chatbot'] = base64_encode(base64_encode($chat->id));
            DB::commit();

            return $this->sendResponse($result, 'Session retrieved successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return $this->sendError('Something went wrong', [], 404);
        }

    }

    public function AdjustChat($data)
    {
        $gender = $data->relation()->first()->session->gender;
        $id_chatbot = base64_encode(base64_encode($data->id));

        return collect($data->json)->filter()->map(function ($item) use ($gender, $id_chatbot) {
            $item['id_chatbot'] = $id_chatbot;
            $item['gender'] = $gender;
            $item['icon'] = $item['operator'] ? env('APP_URL').Helper::_setting_code('web_icon_admin_chatbot') : ($gender == 'L' ? env('APP_URL').Helper::_setting_code('web_icon_user_male') : env('APP_URL').Helper::_setting_code('web_icon_user_female'));

            return $item;
        });
    }

    public function SendArticle($category, $operator = true)
    {
        return [
            'type' => 'article',
            'operator' => $operator,
            'title' => $category['name'],
            'description' => strip_tags($category['overview']),
            'url' => $category['url'],
            'label' => isset($category['label']) ? $category['label'] : Helper::_wording('learn_more', $this->request->lang),
            'date' => date('d M Y H:i:s'),
        ];
    }

    public function SendChoices($category, $children = false, $operator = true)
    {
        return [
            'type' => 'choices',
            'operator' => $operator,
            'question' => strip_tags($category['overview']),
            'choice' => $children ? $children : [
                [
                    'text' => 'Yes',
                    'value' => 'yes',
                ],
                [
                    'text' => 'No',
                    'value' => 'no',
                ],
            ],
            'date' => date('d M Y H:i:s'),
        ];
    }

    public function SendText($category, $operator = true)
    {
        return [
            'type' => 'text',
            'operator' => $operator,
            'question' => strip_tags($category),
            'date' => date('d M Y H:i:s'),
        ];
    }
}
