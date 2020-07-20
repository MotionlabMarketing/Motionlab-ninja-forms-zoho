<?php

/**
 * Retrieve and markup support
 *
 * @author stuartlb3
 */
class ZohoCRM_SupportMarkup {

    /**
     * Marked up data ready for HTML display
     *
     * Values are strings as HTML markup
     * @var array
     */
    protected $marked_up_data;

    /**
     * Retrieves data from sources and marks up for HTML display
     */
    public function __construct() {

        $this->marked_up_data = array(
            NF_ZohoCRM()->constants->refresh_token => '',
        );

        $this->markupRefreshToken();
    }

    /**
     * Retrieve the refresh token from credentials and add to marked up data
     *
     * Token stored as string, so requires no additional markup
     */
    protected function markupRefreshToken() {

        $credentials_array = NF_ZohoCRM()->stored_data->credentials();

        $this->marked_up_data[ NF_ZohoCRM()->constants->refresh_token ] = $credentials_array[ NF_ZohoCRM()->constants->refresh_token ];
    }

    /**
     * Returns the marked up support data
     * @return array
     * @see ZohoCRM_SupportMarkup::marked_up_data
     */
    public function getMarkup() {

        return $this->marked_up_data;
    }

}
