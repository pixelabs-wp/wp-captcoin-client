<?php

class bcProposalViewList
{
    public static function render()
    {
        $access = "notallowed"; // Initialize to a default value
        $showProposals = 4;
        $count = 0;
        $size = 0 ;
        // --- Api Function Call ---
        $ApiResult = bcProposalViewList::ProposalViewList_API();
        $baseurl_for_proposals = get_option('baseurl_for_proposals');
        
        if (isset($_GET['view'])) {
            if ($_GET['view'] == "all") {
                $access = "allowed";
                
            } 
        }
       $baseurl_for_Topics = get_option('baseurl_for_topics_page');

        // --- Decode JSONS Data Result ---
        $proposalViewListJSONS = json_decode($ApiResult, true);
            if (empty($proposalViewListJSONS)) 
            {
            }
            else
            {
                $size = $proposalViewListJSONS["totalSize"];
            }

        // --- Views Generation Part ---
        $view = "";
        $view .= '
        <section class="topics">
            <div class="select-options h-flex">
                <div class="select-value dm-sans-font">
                    
                        <span class="sort-by-icons">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#04091E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="#04091E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="#04091E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        
                        </span> 
                        <select name="" id="">
                            <option value="Sort by" class="dm-sans-font">Sort by</option>
                        </select>
                   
                </div>
                <div class="select-value dm-sans-font">
                    
                        <span class="sort-by-icons"> 
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.75 6C2.75 5.17 3.42 4.5 4.25 4.5C5.08 4.5 5.75 5.17 5.75 6C5.75 6.83 5.08 7.5 4.25 7.5C3.42 7.5 2.75 6.83 2.75 6ZM2.75 12C2.75 11.17 3.42 10.5 4.25 10.5C5.08 10.5 5.75 11.17 5.75 12C5.75 12.83 5.08 13.5 4.25 13.5C3.42 13.5 2.75 12.83 2.75 12ZM4.25 16.5C3.42 16.5 2.75 17.18 2.75 18C2.75 18.82 3.43 19.5 4.25 19.5C5.07 19.5 5.75 18.82 5.75 18C5.75 17.18 5.08 16.5 4.25 16.5ZM21.25 19H7.25V17H21.25V19ZM7.25 13H21.25V11H7.25V13ZM7.25 7V5H21.25V7H7.25Z" fill="black" fill-opacity="0.54"/>
                        </svg>
                        
                        </span> 
                        <select name="" id="">
                            <option value="Category" class="dm-sans-font">Category</option>
                        </select>
                    
                </div>
            </div>
            <div class="crypto-section  dm-sans-font">
            <div class="bcMainContainer">';

        // --- Looping Over List Details Start---
            if (empty($proposalViewListJSONS["list"])) 
            {
            }
            else
            {
                if ($proposalViewListJSONS["list"] != null) 
                {
                    
                    foreach ($proposalViewListJSONS["list"] as  $value) {
                        
                        if ($access == "notallowed" && $showProposals == $count)
                        {
                            break;
                        }
                        
                        // --- Proposal Detail ---
                        $id = $value['id'];
                        $text = $value['text'];
                        $description = $value['description'];
            
                        // --- Basic Result Detail ---
                        $resultType = $value['basicResult']['resultType'];
            
                        // --- Basic Result 1st Answer Detail ---
                        $answerId_1 = $value['basicResult']['answers'][0]['id'];
                        $answer_1 = $value['basicResult']['answers'][0]['answer'];
                        $result_1 = $value['basicResult']['answers'][0]['result'];
            
                        // --- Basic Result 2nd Answer Detail ---
                        $answerId_2 = $value['basicResult']['answers'][1]['id'];
                        $answer_2 = $value['basicResult']['answers'][1]['answer'];
                        $result_2 = $value['basicResult']['answers'][1]['result'];
            
                        // --- Proposal View and status Detail ---
                        $finished = $value['finished'];
                        $endTimestamp = $value['endTimestamp'];
                        $numberOfPeople = $value['numberOfPeople'];
            
                        // --- Proposal Reamining Time Calculated ---
                        $time = time();
                        $compare = strtotime($endTimestamp);
                        $seconds = $compare - $time;
                        $days = floor($seconds / 86400);
                        $seconds %= 86400;
                        $hours = floor($seconds / 3600);
                        $seconds %= 3600;
                        $minutes = floor($seconds / 60);
                        $seconds %= 60;
            
                        // --- Proposal On Page Display ---
                        $view .= '  
                                <a href="' . $baseurl_for_proposals . '?bcsingleid=' . $id . '"> 
                                    <div class="bcChildContainer">
                                        <div class="" style="display: block;">
                                            <p class="bcHeadType">Crypto</p>
                                        </div>
                                        <div class="" style="display: block;">
                                            <p class="bcHeadText">' . $text . '</p>                            
                                        </div>
                                        <div class="bcVoteContainer">
                                            <div class="bcYesContainer">
                                                <p>' . $answer_1 . '</p>
                                            </div>
                                            <div class="bcNoContainer">
                                                <p>' . $answer_2 . '</p>
                                            </div>
                                            
                                            <div class="bcTimeContainer">';
                        if ($finished == true) {
                            $view .= '<span class="bcfinished">Finished</span>';
                        } else {
                            $view .= '<div class="bcdays">' . $days . '
                                                            <span>days</span>
                                                        </div>
                                                        <div class="bchours">' . $hours . '
                                                            <span>hrs</span>
                                                        </div>
                                                        <div class="bcseconds">' . $seconds . '
                                                            <span>secs</span>
                                                        </div>';
                        }
                        $view .= '
                                            </div>
                                        </div>
                                        <div class="bcDescriptionContainer">
                                            <p>' . $description . '</p>
                                        </div>
                                        <div class="bcProposalDetailContainer">
                                            <div class="bcNoOfPeopleContainer">
                                                    <svg width="27" height="31" viewBox="0 0 27 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.0785217 28.3671C0.0785217 29.4717 0.973952 30.3671 2.07852 30.3671H24.936C26.0406 30.3671 26.936 29.4717 26.936 28.3671V2.85889C26.936 1.75432 26.0406 0.858887 24.936 0.858887H2.07852C0.973952 0.858887 0.0785217 1.75432 0.0785217 2.85889V28.3671ZM17.9835 10.6952C17.9835 13.4165 15.9841 15.6132 13.5072 15.6132C11.0304 15.6132 9.03096 13.4165 9.03096 10.6952C9.03096 7.97388 11.0304 5.77716 13.5072 5.77716C15.9841 5.77716 17.9835 7.97388 17.9835 10.6952ZM13.5073 18.7281C10.5232 18.7281 4.55482 20.5313 4.55482 23.81C4.55482 24.7154 5.28879 25.4494 6.19417 25.4494H20.8205C21.7258 25.4494 22.4598 24.7154 22.4598 23.81C22.4598 20.5313 16.4915 18.7281 13.5073 18.7281Z" fill="#36BB91"/>
                                                    </svg>
                                                    <p>' . $numberOfPeople . '</p>
                                            </div>
                                            <div class="bcVotePercentContainer">
                                                <svg width="33" height="34" viewBox="0 0 33 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.08494 12.0063L19.7832 0.23584L22.2302 2.89158L20.5591 11.7113H32.9583V18.9244L27.4525 33.0227H9.08494V12.0063ZM6.10078 13.3506H0.132446V33.0227H6.10078V13.3506Z" fill="#36BB91"/>
                                                    </svg>                        
                                                <p class="bcPercent">' . $result_1 . '%</p>
                                                <svg width="34" height="33" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M24.7962 21.2029L14.0979 32.9734L11.6509 30.3177L13.3221 21.498H0.922852V14.2849L6.42864 0.186523H24.7962V21.2029ZM33.7487 0.186523H27.7803V19.8587H33.7487V0.186523Z" fill="#F94025"/>
                                                    </svg>                                          
                                                <p>' . $result_2 . '%</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>';
                                $count++;
                    }
                }
            }

        // --- Looping Over List Details Ends---
        if($size > 4)
        {
            $view .= '
            </div>  
                </div>
                <div class="topic-btn dm-sans-font">
                    <a href="'.$baseurl_for_Topics.'?view=all" class="dm-sans-font"><button>View More</button></a>
                </div>
            </section>';
        }
        return $view;
    }

    // --- Proposal List Api Function Start ---
    public static function ProposalViewList_API()
    {
        // --- Get Login User Details And Access Token From WP DB ---
        $user = wp_get_current_user();
        $user_ID = $user->ID;
        $cognitoId = get_user_meta($user_ID, 'CC-cognitoId', true);
        
        if (!is_user_logged_in()) {
            $accessToken = $_SESSION["AT"];
        } else {
            $accessToken = get_user_meta($user_ID, 'captCoinAT', true);
        }
        
        $baseurl_for_Apis = get_option('baseurl_for_Apis');

        // --- Api Call  ---
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl_for_Apis . 'tcc/proposals',
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

    // --- Proposal List Api Function Ends ---
}
