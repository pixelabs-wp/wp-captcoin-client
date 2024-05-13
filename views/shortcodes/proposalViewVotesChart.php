<?php

class bcProposalViewVotesChart
{
    public static function render($atts)
    {
        // --- Proposal Id From Url ---
        $id = $_GET['bcsingleid'];


        // --- Api FunctionCall ---
        $ApiResult = bcProposalViewVotesChart::ProposalViewVotesChart_API($id);

        // --- Decode JSONS Data ---
        $proposalViewVoteChartJSONS = json_decode($ApiResult, true);

        // --- Proposal Detail ---
        $id = $proposalViewVoteChartJSONS['id'];
        $text = $proposalViewVoteChartJSONS['text'];
        $description = $proposalViewVoteChartJSONS['description'];

        // --- Basic Result Detail ---
        $resultType = $proposalViewVoteChartJSONS['basicResult']['resultType'];

        // --- Basic Result 1st Answer Detail ---
        $answerId_1 = $proposalViewVoteChartJSONS['basicResult']['answers'][0]['id'];
        $answer_1 = $proposalViewVoteChartJSONS['basicResult']['answers'][0]['answer'];
        $result_1 = $proposalViewVoteChartJSONS['basicResult']['answers'][0]['result'];

        // --- Basic Result 2nd Answer Detail ---
        $answerId_2 = $proposalViewVoteChartJSONS['basicResult']['answers'][1]['id'];
        $answer_2 = $proposalViewVoteChartJSONS['basicResult']['answers'][1]['answer'];
        $result_2 = $proposalViewVoteChartJSONS['basicResult']['answers'][1]['result'];

        // --- Proposal View and status Detail ---
        $finished = $proposalViewVoteChartJSONS['finished'];
        $endTimestamp = $proposalViewVoteChartJSONS['endTimestamp'];
        $numberOfPeople = $proposalViewVoteChartJSONS['numberOfPeople'];

        // --- Proposal View and status Detail ---
        $finished = $proposalViewVoteChartJSONS['finished'];
        $endTimestamp = $proposalViewVoteChartJSONS['endTimestamp'];
        $numberOfPeople = $proposalViewVoteChartJSONS['numberOfPeople'];

        // --- Proposal Result Data ---

        $regular = $proposalViewVoteChartJSONS['results'][0]['resultType'];
        $regularAnswerId_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['id'];
        $regularAnswer_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['answer'];
        $regularAnswerResult_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['result'];

        $regularAnswerId_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['id'];
        $regularAnswer_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['answer'];
        $regularAnswerResult_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['result'];


        $shareholder = $proposalViewVoteChartJSONS['results'][0]['resultType'];
        $shareholderAnswerId_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['id'];
        $shareholderAnswer_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['answer'];
        $shareholderAnswerResult_1 = $proposalViewVoteChartJSONS['results'][0]['answers'][0]['result'];

        $shareholderAnswerId_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['id'];
        $shareholderAnswer_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['answer'];
        $shareholderAnswerResult_2 = $proposalViewVoteChartJSONS['results'][0]['answers'][1]['result'];

        $totalPercentage_Yes = ($regularAnswerResult_1 +  $shareholderAnswerResult_1) / 2;
        $totalPercentage_No = ($regularAnswerResult_2 +  $shareholderAnswerResult_2) / 2;

        // <div class="total-vote-number">
        //         <p>▴ 4.75% </p>
        //     </div>
        //     <div class="signin-text d-flex">
        //     <p>Sign in</p>
        //     <img src="/CaptainsCoin-imgs/Icongt.svg" alt="">
        // </div>

        // --- Views Generation Part ---
        $view = "";
        $view .= '<div class="vote-result-display">
        <div class="vote-result-sub-heading h-flex">
            <p>Votes Results</p>
            <div class="svg-icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="8" fill="#F7F8F9"/>
                    <g clip-path="url(#clip0_188_355)">
                    <path d="M23.3334 12L17.0001 18.3333L13.6667 15L8.66675 20" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19.3333 12H23.3333V16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_188_355">
                    <rect width="16" height="16" fill="white" transform="translate(8 8)"/>
                    </clipPath>
                    </defs>
                </svg>                                
            </div>
        </div>
        <div class="total-votes-text h-flex">
            <div class="total-vote-heading">
                <p>Total Votes</p>
            </div>
            
        </div>
        <div class="yes-percentage h-flex">
            <div class="bcDisplay">
                <div class="yes-percentage-text">
                    <p>' . $answer_1 . '</p>
                </div>
                <div class="yes-percentage-number">
                    <p>' . $totalPercentage_Yes . '%</p>
                </div>
            </div>
            <div class="progress">
                <div class="yes-progress-bar" role="progressbar" style="width: ' . $totalPercentage_Yes . '%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="no-percentage h-flex">
            <div class="bcDisplay">
                <div class="no-percentage-text">
                    <p>' . $answer_2 . '</p>
                </div>
                <div class="no-percentage-number">
                    <p>' . $totalPercentage_No . '%</p>
                </div>
            </div>
            <div class="progress">
                    <div class="no-progress-bar" role="progressbar" style="width: ' . $totalPercentage_No . '%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
    </div>
    <div class="chart-results h-flex">
                <div class="pie-chart-area">
                    <div class="top-charts-area h-flex">
                        <div class="top-area-text">
                            <div class="pie-chart-text">
                            <p class="vrText">Votes Results</p>
                            <div class="regularvote-text">
                                <p>Regular votes</p>
                            </div>
                            </div>
                        </div>
                        <div class="right-reviewimages-area d-flex">
                            <div class="review-imgs">
                                <img src="/CaptainsCoin-imgs/girl-vote-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/women-vote-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/girl-review-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/boy-voter-img.svg" alt="">
                            </div>
                            <div class="icon-right">
                                <img src="/CaptainsCoin-imgs/Icon R.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="pie-chart d-flex yes-no-text">
                        <canvas id="pieChart" width="400" height="400"></canvas>
                        <div class="colors-detail">
                            <div class="yes-color-details d-flex ">
                                <img src="/CaptainsCoin-imgs/Ellipse 13.svg" alt="">
                            <p><svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="5.5" cy="5" r="5" fill="#0FCA7A"/>
                            </svg>
                            ' . $regularAnswer_1 . '</p>
                            </div>
                            <div class="yes-color-details d-flex">
                                <img src="/CaptainsCoin-imgs/Ellipse red.svg" alt="">
                            <p> <svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="5.5" cy="5" r="5" fill="#F94025"/>
                            </svg>
                            ' . $regularAnswer_2 . '</p>
                            </div>
                        </div>
                    </div>
                                         
                </div>
                <div class="doughnut-chart-area">
                    <div class="top-charts-area h-flex">
                        <div class="top-area-text">
                            <div class="pie-chart-text">
                            <p class="vrText">Votes Results</p>
                            <div class="regularvote-text">
                                <p>Shareholder votes</p>
                            </div>
                            </div>
                        </div>
                        <div class="right-reviewimages-area d-flex">
                            <div class="review-imgs">
                                <img src="/CaptainsCoin-imgs/girl-vote-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/women-vote-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/girl-review-img.svg" alt="">
                                <img src="/CaptainsCoin-imgs/boy-voter-img.svg" alt="">
                            </div>
                            <div class="icon-right">
                                <img src="/CaptainsCoin-imgs/Icon R.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="doughnut-chart d-flex yes-no-text">
                        <canvas id="myChart" width="400" height="400"></canvas>
                        <div class="colors-detail">
                            <div class="yes-color-details d-flex ">
                                <img src="/CaptainsCoin-imgs/Ellipse 13.svg" alt="">
                            <p>
                            <svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="5.5" cy="5" r="5" fill="#0FCA7A"/>
                            </svg>
                            ' . $shareholderAnswer_1 . '</p>
                            </div>
                            <div class="yes-color-details d-flex">
                                <img src="/CaptainsCoin-imgs/Ellipse red.svg" alt="">
                            <p><svg width="11" height="10" viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="5.5" cy="5" r="5" fill="#F94025"/>
                            </svg>
                            ' . $shareholderAnswer_2 . '</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- javaScript For Pie Chart and Doughnut Chart -->
            <script>
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: "doughnut",  
                    data: {
                        datasets: [{
                            label: "Shareholder votes",
                            data: [' . $shareholderAnswerResult_1 . ', ' . $shareholderAnswerResult_2 . '],
                            backgroundColor: [
                            "#36BB91",
                            "#F94025"
                            
                            ],
                            hoverOffset: 4
                        }]
                    }
                });
            </script>
            <script>
                var ctxa = document.getElementById("pieChart");
                var pieChart = new Chart(ctxa, {
                    type: "pie",  
                    data: {
                datasets: [{
                    label: "Regular votes",
                    data: [' . $regularAnswerResult_1 . ', ' . $regularAnswerResult_2 . '],
                    backgroundColor: [
                    "#36BB91",
                    "#F94025"
                    ],
                    hoverOffset: 4
                }]
                }
            });
            </script>';
        return $view;
    }

    // --- Proposal By Id Api Function Start ---
    public static function ProposalViewVotesChart_API($proposalId)
    {

        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
//         $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        $baseurl_for_Apis = get_option('baseurl_for_Apis');

		if (!is_user_logged_in()) {
            $accessToken = $_SESSION["AT"];
        } else {
            $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        }
		
        // --- Api Call  ---
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals/' . $proposalId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken
            ),
        ));

        // --- Api Response  ---
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    // --- Proposal By Id Api Function End ---
}
