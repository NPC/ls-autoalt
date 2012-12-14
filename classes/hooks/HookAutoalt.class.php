<?php

/**
 *		Comment Filter
 *		a LiveStreet plugin
 *		by Anton Maslo (http://amaslo.com)
 *		originally developed for MMOzgoved (http://www.mmozg.net/) project
 */

class PluginAutoalt_HookAutoalt extends Hook { 
	
	
	public function RegisterHook() {
		// Add alt attribute
		if(Config::Get('plugin.autoalt.add_alt')) {
			$this->AddHook('topic_add_after', 'AddImageAlt', __CLASS__);
        	$this->AddHook('topic_edit_after', 'AddImageAlt', __CLASS__);
        }
	}
	
	/**
     * Adds / modifies ALT tag of images
     * (code based on TopicExtend plugin)
     *
     * @param type $aVars
     */
    public function AddImageAlt($aVars) {
        if (isset($aVars['oTopic'])) {
        	// Get topic name
            $oTopic = $aVars['oTopic'];
            $oTopic = $this->Topic_GetTopicById($oTopic->getId());
            $sTopicTitle = $oTopic->getTitle();
            $sBlogTitle = NULL;
            // whether blog should be added to title
            $bConcatenate = TRUE;

            // Get blog name
            if (Config::Get('plugin.autoalt.include_blog_name')) {
	            $iBlogId = $oTopic->getBlogId();
	            if (isset($iBlogId)) {
	            	$sBlogTitle = $this->Blog_GetBlogById($iBlogId)->GetTitle();

                    // If blog name already contained in topic title - don't add it
                    if(strpos($sTopicTitle, $sBlogTitle) !== FALSE)
                        $bConcatenate = FALSE;
	            }
	        } else {
	        	$sBlogTitle = NULL;
	        }

            if ($sText = $this->_addImageParam(
	            	$oTopic->getTextSource(), 
	            	$this->_miniSanitize($sTopicTitle), 
	            	(isset($sBlogTitle) ? $this->_miniSanitize($sBlogTitle) : null),
                    $bConcatenate))
            {
            	// Update topic with the results of parsing
                // Check for presence of AutoCut plugin to avoid removing its cutting logic
                if(!class_exists('PluginAutocut')) {
                    // No autocut - use standard cutting
                    list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($sText);
                } else {
                    // AutoCut present - use its cutting mechanism
                    list($sTextShort, $sTextNew, $sTextCut) = $this->Text_Cut($this->PluginAutocut_Autocut_CutAdd($sText));
                }

                $oTopic->setCutText($sTextCut);
                $oTopic->setText($this->Text_Parser($sTextNew));
                $oTopic->setTextShort($this->Text_Parser($sTextShort));
                $oTopic->setTextSource($sText);
                $oTopic->setTextHash(md5($sText));
                $this->Topic_UpdateTopic($oTopic);
            }
        }
    }

    // Remove quotes from text
    protected function _miniSanitize($sText) {
        return preg_replace('/"/', '', $sText);
    }

    /**
     * Makes changes to the topic text
     * (code based on TopicExtend plugin)
     *
     * @param type $sText
     * @param type $sInsert
     * @return type
     */
    protected function _addImageParam($sText, $sTopic, $sBlog, $bConcatenate) {
        $sTextNew = '';
        
        // Find all images
        $patternImg = "(<img[^<>+]*>)";

        // find all images first
        if (preg_match_all($patternImg, $sText, $aMatches) > 0) {
            $aMatchesImg = $aMatches[0];
            
            $iLast = count($aMatchesImg) - 1;
            
            // Loop through all the images
            foreach ($aMatchesImg as $key => $sImg) {
                // default inserted alt (applied if no alt present)
                $sInsert = ' alt="'.($bConcatenate && isset($sBlog) ? $sBlog.': ' : '').$sTopic.'"';
                $changeFlag = TRUE;

				if (preg_match('/alt=""/', $sImg)) {
                    // Empty alt found - no change to $sInsert
            	} elseif (preg_match('/alt="([^"]+)"/', $sImg, $sAltText)) {
            		// Non-empty alt found
            		// Don't add if no blog - or blog name already present
                    if (isset($sBlog) && strpos($sAltText[1], $sBlog) === FALSE) {
                        $sInsert = ' alt="'.$sBlog.': '.$sAltText[1].'"';
                    } else {
                        // Blog name not needed or included, no change
                        $changeFlag = FALSE;
                    }
        		}

                if($changeFlag) {
                    // Remove existing alt
                    $sImgNew = preg_replace('/[ \t]*alt.?=[^".]?".*?"/', '', $sImg);
                    // Insert modified alt
                    $sImgNew = preg_replace('/[ \t]*\/?>/', $sInsert . ' />', $sImgNew);

                    $sText = str_replace($sImg, $sImgNew, $sText);
                }
            }

            $sTextNew = $sText;
        }

        return $sTextNew;
    }

  }

?>