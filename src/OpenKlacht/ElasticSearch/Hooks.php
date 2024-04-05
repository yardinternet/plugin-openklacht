<?php

namespace OWC\OpenKlacht\ElasticSearch;

class Hooks
{
    public function extendSyncArgs($args, $postID)
    {
        if ('Klacht' !== $args['doctype']) {
            return $args;
        }

        $args['okl_judgement_date'] = get_post_meta($postID, 'okl_judgement_date', true) ?? '';
        $args['okl_judgement_year'] = get_post_meta($postID, 'okl_judgement_year', true) ?? '';
        $args['okl_date_received'] = get_post_meta($postID, 'okl_date_received', true) ?? '';
        $args['okl_year_received'] = get_post_meta($postID, 'okl_year_received', true) ?? '';
        $args['okl_judgement'] = get_post_meta($postID, 'okl_judgement', true) ?? '';

        return $args;
    }
}
