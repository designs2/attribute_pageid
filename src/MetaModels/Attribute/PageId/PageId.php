<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage AttributeUrl
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Attribute\PageId;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use MetaModels\Attribute\BaseSimple;
use MetaModels\DcGeneral\Events\PageIdWizardHandler;

/**
 * This is the MetaModelAttribute class for handling urls.
 *
 * @package    MetaModels
 * @subpackage AttributeUrl
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 */
class PageId extends BaseSimple
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDataType()
    {
        return 'blob NULL';
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeSettingNames()
    {
        return array_merge(parent::getAttributeSettingNames(), array(
            'no_external_link',
            'mandatory',
            'trim_title'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function valueToWidget($varValue)
    {
        if ($this->get('trim_title') && is_array($varValue)) {
            $varValue = $varValue[1];
        }

        return parent::valueToWidget($varValue);
    }

    /**
     * {@inheritdoc}
     */
    public function widgetToValue($varValue, $intId)
    {
        if ($this->get('trim_title') && !is_array($varValue)) {
            $varValue = array(0 => '', 1 => $varValue);
        }

        return parent::widgetToValue($varValue, $intId);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldDefinition($arrOverrides = array())
    {
        $arrFieldDef = parent::getFieldDefinition($arrOverrides);

        $arrFieldDef['inputType'] = 'text';
        if (!isset($arrFieldDef['eval']['tl_class'])) {
            $arrFieldDef['eval']['tl_class'] = '';
        }
        $arrFieldDef['eval']['tl_class'] .= ' wizard inline';

        if (!$this->get('trim_title')) {
            $arrFieldDef['eval']['size']      = 1;
            $arrFieldDef['eval']['multiple']  = false;
            $arrFieldDef['eval']['tl_class'] .= ' metamodelsattribute_pageid';
        }

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getMetaModel()->getServiceContainer()->getEventDispatcher();
        $dispatcher->addListener(
            ManipulateWidgetEvent::NAME,
            array(new PageIdWizardHandler($this->getMetaModel(), $this->getColName()), 'getWizard')
        );

        return $arrFieldDef;
    }
}
