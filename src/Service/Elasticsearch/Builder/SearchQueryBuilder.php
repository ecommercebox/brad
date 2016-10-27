<?php

namespace Invertus\Brad\Service\Elasticsearch\Builder;

use Context;
use Core_Business_ConfigurationInterface as ConfigurationInterface;

class SearchQueryBuilder extends AbstractQueryBuilder
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * SearchQueryBuilder constructor.
     *
     * @param ConfigurationInterface $configuration
     * @param Context $context
     */
    public function __construct(ConfigurationInterface $configuration, Context $context)
    {
        parent::__construct($context);

        $this->configuration = $configuration;
    }

    /**
     * Build search query
     *
     * @param string $query
     * @param int|null $from
     * @param int|null $size
     * @param string|null $sortBy
     * @param string|null $sortWay
     *
     * @return array
     */
    public function buildProductsQuery($query, $from = null, $size = null, $sortBy = null, $sortWay = null)
    {
        $idLang = (int) $this->context->language->id;

        $query = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'match_phrase_prefix' => [
                                'name_lang_'.$idLang => [
                                    'query' => $query,
                                    'boost' => 3,
                                ],
                            ],
                        ],
                        [
                            'match_phrase_prefix' => [
                                'description_lang_'.$idLang => [
                                    'query' => $query,
                                    'boost' => 1.5,
                                ],
                            ],
                        ],
                        [
                            'match_phrase_prefix' => [
                                'short_description_lang_'.$idLang => [
                                    'query' => $query,
                                    'boost' => 1.5,
                                ],
                            ],
                        ],
                        [
                            'match_phrase_prefix' => [
                                'manufacturer_name' => [
                                    'query' => $query,
                                ],
                            ],
                        ],
                        [
                            'match_phrase_prefix' => [
                                'reference' => [
                                    'query' => $query,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        if (null !== $from) {
            $query['from'] = (int) $from;
        }

        if (null !== $size) {
            $query['size'] = (int) $size;
        }

        if (null !== $sortBy && null != $sortWay) {
            $query['sort'] = $this->buildSort($sortBy, $sortWay);
        }

        return $query;
    }
}
