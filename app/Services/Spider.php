<?php

namespace App\Services;

use App\Models\Data;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDomInterface;

class Spider
{
    const baseUrl = "https://yayponies.no/";

    const tasks = [
        "g4_episodes" => [
            "url" => "videos/tables/1i?.html",
            "yield" => [
                "field" => "episode_season",
                "values" => [1, 2, 3, 4, 5, 6, 7, 8, 9]
            ],
            "columns" => [
                0 => [
                    "pattern" => '/Episode ([0-9]*).+?\((.*)\)/',
                    "important" => true,
                    "matches" => [
                        1 => "episode_n",
                        2 => "episode_title"
                    ]
                ],
                1 => [
                    "pattern" => '/<a href="(.+?)">[0-9]*<\/a>/',
                    "multi" => "disk_download",
                    "url" => true,
                    "matches" => 1
                ],
                2 => [
                    "extend" => 1,
                    "multi" => "direct_download"
                ],
                3 => [
                    "extend" => 1,
                    "multi" => "torrent_download"
                ]
            ]
        ],
        "g4_movies" => [
            "url" => "videos/movies/tables/moviesit.html",
            "columns" => [
                0 => [
                    "pattern" => '/Episode [0-9]* \((.*)\) - 1080P/',
                    "important" => true,
                    "matches" => [
                        1 => "episode_title"
                    ]
                ],
                1 => [
                    "pattern" => '/<a href="(.+?)">[0-9]*<\/a>/',
                    "multi" => "disk_download",
                    "url" => true,
                    "matches" => 1
                ],
                2 => [
                    "extend" => 1,
                    "multi" => "direct_download"
                ],
                3 => [
                    "extend" => 1,
                    "multi" => "torrent_download"
                ]
            ]
        ]
    ];

    /**
     * 处理所有任务
     */
    public static function run()
    {
        foreach (self::tasks as $taskName => $taskOptions) {
            if (App::runningInConsole()) print("Running: Task {$taskName}\n");

            $result = [];

            if ($yieldField = $taskOptions["yield"]["field"] ?? null) {
                foreach ($taskOptions["yield"]["values"] as $yieldValue) {
                    $url = Str::replaceArray("?", [$yieldValue], $taskOptions["url"]);
                    $result[$yieldValue] = self::parseUrl($url, $taskOptions["columns"]);
                }
            } else $result = self::parseUrl($taskOptions["url"], $taskOptions["columns"]);

            $obj = new Data;
            $obj->task = $taskName;
            $obj->data = $result;
            $obj->save();
        }
    }

    /**
     * 处理单个 URL
     *
     * @param string $url
     * @param array $columns $taskOptions["columns"]
     * @return array
     */
    public static function parseUrl($url, $columns)
    {
        $result = [];

        $response = Spider::client()->get($url);
        $html = $response->getBody()->getContents();
        $dom = HtmlDomParser::str_get_html($html);

        if (App::runningInConsole()) print("Parsing: {$url}\n");

        $rows = $dom->findMulti("tr");
        foreach ($rows as $row) {
            $itemResult = self::parseRow($row, $columns);
            if (!empty($itemResult)) array_push($result, $itemResult);
        }

        return $result;
    }

    /**
     * 处理单行数据
     *
     * @param SimpleHtmlDomInterface $row
     * @param array $columns $taskOptions["columns"]
     * @return array|null
     */
    public static function parseRow($row, $columns) {
        $result = [];
        $cols = $row->findMulti("th, td");

        foreach ($columns as $i => $column) {
            $ele = $cols[$i] ?? null;
            if (filled($ele)) {
                $eleText = $ele->text;
                $eleHtml = $ele->html;
            } else return null;

            $options = self::parseConfig($columns, $i);

            if (filled($options["multi"] ?? null)) {
                $colResult = [];

                preg_match_all($options["pattern"], $eleHtml, $matches, PREG_SET_ORDER);

                foreach ($matches as $matchRowId => $matchRow) {
                    if (is_array($options["matches"])) {
                        $matchResult = [];
                        foreach ($options["matches"] as $matchId => $matchName) {
                            if (filled($matchRow[$matchId] ?? null)) {
                                $matchResult[$matchName] = ($options["url"] ?? false)
                                    ? self::urlAbs($matchRow[$matchId])
                                    : $matchRow[$matchId];
                            }
                        }
                    } else {
                        $matchResult = $matchRow[$options["matches"]] ?? null;
                        $matchResult = ($options["url"] ?? false)
                            ? self::urlAbs($matchResult) : $matchResult;
                    }
                    if (filled($matchResult)) array_push($colResult, $matchResult);
                }

                $result[$options["multi"]] = $colResult;
            } else {
                preg_match($options["pattern"], $eleText, $matchRow);

                foreach ($options["matches"] as $matchId => $matchName) {
                    if (filled($matchRow[$matchId] ?? null)) {
                        $result[$matchName] = $matchRow[$matchId];
                    } elseif ($options["important"] === true) return null;
                }
            }
        }

        return $result;
    }

    /**
     * 处理配置继承
     *
     * @param array $columns $taskOptions["columns"]
     * @param integer $i
     * @return array $taskOptions["columns"][$i]
     */
    public static function parseConfig($columns, $i) {
        $config = $columns[$i] ?? [];
        if (isset($config["extend"])) {
            $config = array_merge(
                self::parseConfig($columns, $config["extend"]),
                $config
            );
        }
        return $config;
    }

    /**
     * 将相对 URL 转为绝对 URL
     *
     * @param string $url
     * @return string
     */
    public static function urlAbs($url) {
        if (Str::startsWith($url, "/")) {
            $url = Str::replaceFirst("/", self::baseUrl, $url);
        }
        return $url;
    }

    /**
     * 获取一个 HTTP 客户端
     *
     * @return Client
     */
    public static function client()
    {
        $options = [
            "base_uri" => self::baseUrl,
            "timeout" => 30,
            "headers" => [
                "User-Agent" => "Mozilla/5.0 (JWS GuzzleHTTP ts-res/1.0)"
            ]
        ];

        /*
        if (App::environment("local")) {
            $options["verify"] = false;
            $options["proxy"] = "http://127.0.0.1:1080/";
        }
        */

        return new Client($options);
    }
}
