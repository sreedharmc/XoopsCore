<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Publisher
 * @subpackage      Blocks
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

include_once dirname(dirname(__FILE__)) . '/include/common.php';

function publisher_items_new_show($options)
{
    $publisher = Publisher::getInstance();

    $selectedcatids = explode(',', $options[0]);

    $block = array();
    if (in_array(0, $selectedcatids)) {
        $allcats = true;
    } else {
        $allcats = false;
    }

    $sort = $options[1];
    $order = PublisherUtils::getOrderBy($sort);
    $limit = $options[3];
    $start = 0;
    $image = $options[5];

    // creating the ITEM objects that belong to the selected category
    if ($allcats) {
        $criteria = null;
    } else {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('categoryid', '(' . $options[0] . ')', 'IN'));
    }
    $itemsObj = $publisher->getItemHandler()->getItems($limit, $start, array(_PUBLISHER_STATUS_PUBLISHED), -1, $sort, $order, '', true, $criteria, true);

    $totalitems = count($itemsObj);
    if ($itemsObj) {
        for ($i = 0; $i < $totalitems; $i++) {

            $item = array();
            $item['link'] = $itemsObj[$i]->getItemLink(false, isset($options[4]) ? $options[4] : 65);
            $item['id'] = $itemsObj[$i]->getVar('itemid');
            $item['poster'] = $itemsObj[$i]->posterName(); // for make poster name linked, use linkedPosterName() instead of posterName()

            if ($image == 'article') {
                $item['image'] = XOOPS_URL . '/uploads/blank.gif';
                $item['image_name'] = '';
                $images = $itemsObj[$i]->getImages();
                if (is_object($images['main'])) {
                    // check to see if GD function exist
                    if (!function_exists('imagecreatetruecolor')) {
                        $item['image'] = XOOPS_URL . '/uploads/images/' . $images['main']->getVar('image_name');
                    } else {
                        $item['image'] = PUBLISHER_URL . '/thumb.php?src=' . XOOPS_URL . '/uploads/images/' . $images['main']->getVar('image_name') . '&amp;w=50';
                    }
                    $item['image_name'] = $images['main']->getVar('image_nicename');
                }
            } elseif ($image == 'category') {
                $item['image'] = $itemsObj[$i]->getCategoryImagePath();
                $item['image_name'] = $itemsObj[$i]->getCategoryName();
            } elseif ($image == 'avatar') {
                if ($itemsObj[$i]->getVar('uid') == '0') {
                    $item['image'] = XOOPS_URL . '/uploads/blank.gif';
                    $images = $itemsObj[$i]->getImages();
                    if (is_object($images['main'])) {
                        // check to see if GD function exist
                        if (!function_exists('imagecreatetruecolor')) {
                            $item['image'] = XOOPS_URL . '/uploads/images/' . $images['main']->getVar('image_name');
                        } else {
                            $item['image'] = PUBLISHER_URL . '/thumb.php?src=' . XOOPS_URL . '/uploads/' . $images['main']->getVar('image_name') . '&amp;w=50';
                        }
                    }
                } else {
                    // check to see if GD function exist
                    if (!function_exists('imagecreatetruecolor')) {
                        $item['image'] = XOOPS_URL . '/uploads/' . $itemsObj[$i]->posterAvatar();
                    } else {
                        $item['image'] = PUBLISHER_URL . '/thumb.php?src=' . XOOPS_URL . '/uploads/' . $itemsObj[$i]->posterAvatar() . '&amp;w=50';
                    }
                }
                $item['image_name'] = $itemsObj[$i]->posterName();
            }

            $item['title'] = $itemsObj[$i]->title();

            if ($sort == "datesub") {
                $item['new'] = $itemsObj[$i]->datesub();
            } elseif ($sort == "counter") {
                $item['new'] = $itemsObj[$i]->getVar('counter');
            } elseif ($sort == "weight") {
                $item['new'] = $itemsObj[$i]->weight();
            }

            $block['newitems'][] = $item;
        }
    }

    $block['show_order'] = $options[2];

    return $block;
}

function publisher_items_new_edit($options)
{
    $form = new PublisherBlockForm();

    $catEle = new XoopsFormLabel(_MB_PUBLISHER_SELECTCAT, PublisherUtils::createCategorySelect($options[0], 0, true, 'options[0]'));
    $orderEle = new XoopsFormSelect(_MB_PUBLISHER_ORDER, 'options[1]', $options[1]);
    $orderEle->addOptionArray(array(
        'datesub' => _MB_PUBLISHER_DATE,
        'counter' => _MB_PUBLISHER_HITS,
        'weight'  => _MB_PUBLISHER_WEIGHT,
    ));

    $showEle = new XoopsFormRadioYN(_MB_PUBLISHER_ORDER_SHOW, 'options[2]', $options[2]);
    $dispEle = new XoopsFormText(_MB_PUBLISHER_DISP, 'options[3]', 10, 255, $options[3]);
    $charsEle = new XoopsFormText(_MB_PUBLISHER_CHARS, 'options[4]', 10, 255, $options[4]);

    $imageEle = new XoopsFormSelect(_MB_PUBLISHER_IMAGE_TO_DISPLAY, 'options[5]', $options[5]);
    $imageEle->addOptionArray(array(
        'none' => XoopsLocale::NONE,
        'article' => _MB_PUBLISHER_IMAGE_ARTICLE,
        'category' => _MB_PUBLISHER_IMAGE_CATEGORY,
        'avatar'  => _MB_PUBLISHER_IMAGE_AVATAR,
    ));

    $form->addElement($catEle);
    $form->addElement($orderEle);
    $form->addElement($showEle);
    $form->addElement($dispEle);
    $form->addElement($charsEle);
    $form->addElement($imageEle);

    return $form->render();
}