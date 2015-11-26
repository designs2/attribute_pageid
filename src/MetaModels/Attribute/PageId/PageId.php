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

use ContaoCommunityAlliance\DcGeneral\Contao\Compatibility\DcCompat;
use ContaoCommunityAlliance\DcGeneral\Factory\DcGeneralFactory;
use ContaoCommunityAlliance\Translator\Contao\LangArrayTranslator;
use ContaoCommunityAlliance\Translator\TranslatorChain;
use ContaoCommunityAlliance\DcGeneral\DC_General;
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
            'mandatory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function valueToWidget($varValue)
    {
       
        $insertTag      = array('{{link_url::', '}}'); 
        $varValue       = str_replace($insertTag, '', $varValue);

        // Define the current ID.
        // $strTable       = $this->getMetaModel()->get('tableName');
        // $intId          = explode('::',\Input::get('id'));

        // //var_dump( $intId[1]);

       
        // $dc             = new DC_General($strTable);
        // $environment    = $dc->getEnvironment();

        // $dispatcher     = $GLOBALS['container']['event-dispatcher'];

        // $translator     = new TranslatorChain();
        // $translator->add(new LangArrayTranslator($dispatcher));

        // $factory             = new DcGeneralFactory();
        // $this->itemContainer = $factory
        //     ->setContainerName($strTable)
        //     ->setTranslator($translator)
        //     ->setEventDispatcher($environment->getEventDispatcher())
        //     ->createDcGeneral();

        // $DataContainer = new DcCompat($this->itemContainer->getEnvironment());
        //  //get the page model to get the current title and alias to set it to the category
        // //var_dump($varValue,\Input::get('page'));

        // $pageModel = \PageModel::findById($varValue);

        // $DataProvider = $this->itemContainer->getEnvironment()->getDataProvider();
        // $model        = $DataProvider->fetch($DataProvider->getEmptyConfig()->setId($intId[1]));
        // // $ProbNameArr  = $model->getItem()->get('name');

        // $model->setProperty('name',$pageModel->title);
        // $model->setProperty('alias',$pageModel->alias);
        // $model->getItem()->save();

        return parent::valueToWidget($varValue);
    }

    /**
     * {@inheritdoc}
     */
    public function widgetToValue($varValue, $intId)
    {
        $insertTag   = array('{{link_url::', '}}'); 
        $varValue    = str_replace($insertTag, '', $varValue);


      //   // Define the current ID.
      //   $strTable       = $this->getMetaModel()->get('tableName');
      // //  $intId          = explode('::',\Input::get('id'));

      //   //var_dump( $intId[1]);

       
      //   $dc             = new DC_General($strTable);
      //   $environment    = $dc->getEnvironment();

      //   $dispatcher     = $GLOBALS['container']['event-dispatcher'];

      //   $translator     = new TranslatorChain();
      //   $translator->add(new LangArrayTranslator($dispatcher));

      //   $factory             = new DcGeneralFactory();
      //   $this->itemContainer = $factory
      //       ->setContainerName($strTable)
      //       ->setTranslator($translator)
      //       ->setEventDispatcher($environment->getEventDispatcher())
      //       ->createDcGeneral();

      //   $DataContainer = new DcCompat($this->itemContainer->getEnvironment());
      //    //get the page model to get the current title and alias to set it to the category
      //   //var_dump($varValue,\Input::get('page'));

      //   $pageModel = \PageModel::findById($varValue);

      //   $DataProvider = $this->itemContainer->getEnvironment()->getDataProvider();
      //   $model        = $DataProvider->fetch($DataProvider->getEmptyConfig()->setId($intId));

      //   $model->setProperty('name',$pageModel->title);
      //   $model->setProperty('alias',$pageModel->alias);
      //   $model->getItem()->save();

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
        $arrFieldDef['eval']['tl_class'] .= ' metamodelsattribute_pageid';

        /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getMetaModel()->getServiceContainer()->getEventDispatcher();
        $dispatcher->addListener(
            ManipulateWidgetEvent::NAME,
            array(new PageIdWizardHandler($this->getMetaModel(), $this->getColName()), 'getWizard')
        );

        return $arrFieldDef;
    }

     /**
     * This method is called to store the data for certain items to the database.
     *
     * @param mixed $arrValues The values to be stored into database. Mapping is item id=>value.
     *
     * @return void
     */
    public function setDataFor($arrValues)
    {
        $strTable   = $this->getMetaModel()->getTableName();
        $strColName = $this->getColName();

         //get the page model to get the current title and alias to set it to the category
            // 

            // $model->setProperty('name',$pageModel->title);
            // $model->setProperty('alias',$pageModel->alias);
            // $model->getItem()->save();  
            //var_dump($strColName,$arrValues); 

        foreach ($arrValues as $intId => $varData) {
            if (is_array($varData)) {
                $varData = serialize($varData);
            }
       
          
            $pageModel = \PageModel::findById($varData);
            $AttributeIsTranslatedData = function($attribute){
               return in_array('setTranslatedDataFor',get_class_methods($this->getMetaModel()->getAttribute($attribute)));
            };
              //  var_dump( $this->getMetaModel()->getAttribute('name'));
                if($AttributeIsTranslatedData('name') || $AttributeIsTranslatedData('alias')){
                    echo('You use translated attributes for name or alias. You have to use the attribute type of "attribute_translatedpageid"');
                    exit;
                }elseif(Null === $this->getMetaModel()->getAttribute('name') || Null === $this->getMetaModel()->getAttribute('alias')){
                    echo('You need the fields "name" and "alias" in your table:'.$strTable);
                    exit;
                }else{
                  $this->getMetaModel()->getServiceContainer()->getDatabase()
                  ->prepare(
                    sprintf('UPDATE %s
                      SET %s=? , %s=? ,%s=?
                      WHERE id=%s', 
                      $strTable, $strColName,'name','alias', $intId))
                  ->execute($varData,$pageModel->title,$pageModel->alias);
                }
                

              
            
        }
    }

    
}
