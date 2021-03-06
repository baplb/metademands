<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 Metademands plugin for GLPI
 Copyright (C) 2018-2019 by the Metademands Development Team.

 https://github.com/InfotelGLPI/metademands
 -------------------------------------------------------------------------

 LICENSE

 This file is part of Metademands.

 Metademands is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Metademands is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Metademands. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */
 
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

require_once(GLPI_ROOT . "/plugins/metademands/fpdf/fpdf.php");

/**
 * Class PluginMetaDemandsMetaDemandPdf
 */
class PluginMetaDemandsMetaDemandPdf extends FPDF {
   /* Constantes pour paramétrer certaines données. */

   var $line_height               = 6;     // Hauteur d'une ligne simple.
   var $new_height                = 0;
   var $day_width                 = 6;       // Largeur de cellule jour
   var $generalinformations_width = 125;     // Largeur de cellule information générale
   var $total_width               = 12;
   var $activityname_width        = 45;
   var $pol_def                   = 'arial'; // Police par défaut;
   var $tail_pol_def              = 11;      // Taille par défaut de la police.
   var $tail_titre                = 14;      // Taille du titre.
   var $margin_top                = 10;      // Marge du haut.
   var $margin_left               = 10;       // Marge de gauche et de droite accessoirement.
   var $largeur_grande_cell       = 210;     // Largeur d'une cellule qui prend toute la page.
   var $hauteurFeuille            = 297;
   var $hauteurHautDePage         = 30;
   var $hauteurPiedDePage         = 10;
   var $largeurPage;
   var $fields;
   var $titre;
   var $sousTitre;

   /**
    * PluginMetaDemandsMetaDemandPdf constructor.
    *
    * @param $titre
    * @param $sousTitre
    */
   public function __construct($titre, $sousTitre) {
      parent::__construct('P', 'mm', 'A4');

      $this->titre              = $titre;
      $this->sousTitre          = $sousTitre;
      $this->largeurPage        = $this->largeur_grande_cell - ($this->margin_left * 2);
      $this->activityname_width = $this->largeurPage / 4;
   }

   /**
    * Fonctions permettant définir la couleur du texte
    */
   function SetFontGrey() {
      $this->SetTextColor(205, 205, 205);
   }

   function SetFontRed() {
      $this->SetTextColor(255, 0, 0);
   }

   function SetFontBlue() {
      $this->SetTextColor(153, 204, 255);
   }

   function SetFontDarkBlue() {
      $this->SetTextColor(0, 0, 255);
   }

   function SetFontBlack() {
      $this->SetTextColor(0, 0, 0);
   }

   /**
    * @param $color
    */
   function SetFontColor($color) {
      switch ($color) {
         case 'grey':
            $this->SetFontGrey();
            break;
         case 'black':
            $this->SetFontBlack();
            break;
         case 'red':
            $this->SetFontRed();
            break;
         case 'blue':
            $this->SetFontBlue();
            break;
         case 'darkblue':
            $this->SetFontDarkBlue();
            break;
         default:
            $this->SetFontBlack();
            break;
      }
   }

   /**
    * Fonctions permettant remplir la couleur d'une cellule
    */
   function SetBackgroundGrey() {
      $this->SetFillColor(225, 225, 215);
   }

   function SetBackgroundHardGrey() {
      $this->SetFillColor(192, 192, 192);
   }

   function SetBackgroundBlue() {
      $this->SetFillColor(185, 218, 255);
   }

   function SetBackgroundRed() {
      $this->SetFillColor(255, 0, 0);
   }

   function SetBackgroundYellow() {
      $this->SetFillColor(255, 255, 204);
   }

   function SetBackgroundWhite() {
      $this->SetFillColor(255, 255, 255);
   }

   /**
    * @param $color
    */
   function SetBackgroundColor($color) {
      switch ($color) {
         case 'grey':
            $this->SetBackgroundGrey();
            break;
         case 'hardgrey':
            $this->SetBackgroundHardGrey();
            break;
         case 'red':
            $this->SetBackgroundRed();
            break;
         case 'blue':
            $this->SetBackgroundBlue();
            break;
         case 'yellow':
            $this->SetBackgroundYellow();
            break;
         case 'white':
            $this->SetBackgroundWhite();
            break;
         default :
            $this->SetBackgroundWhite();
            break;
      }
   }

   /**
    * Permet de dessiner une cellule.
    *
    * @param type $w
    * @param type $h
    * @param type $value
    * @param type $border
    * @param type $align
    * @param type $color
    * @param type $bold
    * @param type $size
    * @param type $fontColor
    */
   function CellValue($w, $h, $value, $border = 'LRB', $align = 'L', $color = '', $bold = false, $size = 12, $fontColor = '') {
      if (empty($size)) {
         $size = $this->tail_pol_def;
      }
      $this->SetBackgroundColor($color);
      $this->SetFontNormal($fontColor, $bold, $size);
      $this->Cell($w, $h, $value, $border, 0, $align, 1);
   }

   /**
    * Permet de dessiner une cellule multiligne.
    *
    * @param type $w
    * @param type $h
    * @param type $value
    * @param type $border
    * @param type $align
    * @param type $color
    * @param type $bold
    * @param type $size
    * @param type $fontColor
    */
   function MultiCellValue($w, $h, $value, $border = 'LRB', $align = 'C', $color = '', $bold = false, $size = 12, $fontColor = '') {
      if (empty($size)) {
         $size = $this->tail_pol_def;
      }
      $this->SetBackgroundColor($color);
      $this->SetFontNormal($fontColor, $bold, $size);
      $this->MultiCell($w, $h, $value, $border, $align, 1);
   }

   /**
    * Redéfinit une fonte
    *
    * @param type $color
    * @param type $bold
    * @param type $size
    */
   function SetFontNormal($color, $bold, $size) {
      $this->SetFontColor($color);
      if ($bold) {
         $this->SetFont($this->pol_def, 'B', $size);
      } else {
         $this->SetFont($this->pol_def, '', $size);
      }
   }

   /**
    * @param $form
    * @param $fields
    */
   public function setFields($form, $fields) {

      $fielCount       = 0;
      $lineCount       = 0;
      $lineNumber      = [];
      $lastField       = [];
      $rank            = 1;

      $newForm = [];
      foreach ($form as $key => $elt) {
         if (isset($fields['fields'][$key]) || $elt['type'] == 'title') {
            $newForm[$fielCount] = $elt;
            if ($rank != $elt['rank']) {
               $newForm[$fielCount] = ['type' => 'linebreak', 'rank' => $elt['rank'], 'id' => 0];
               $fielCount++;
               $newForm[$fielCount] = $elt;
            }
            $rank = $elt['rank'];

            $fielCount++;
         }
      }
      $dbu = new DbUtils();
      $fielCount = 0;
      foreach ($newForm as $key => $elt) {
         if (isset($fields['fields'][$elt['id']]) || $elt['type'] == 'title' || $elt['type'] == 'linebreak') {
            $lastField[$fielCount] = $elt;
            $value                 = null;

            $lineNumber[$lineCount][$key]           = 1;
            $lineNumber[$lineCount][$key . 'label'] = 1;

            switch ($elt['type']) {
               case 'textarea':
                  $value                                  = $fields["fields"][$elt['id']];
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width - 10,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          '', 'L', '', 0, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width - 10,
                                                                                          $this->line_height,
                                                                                          str_replace(['\r\n', '\n'], "\n", $value),
                                                                                          '', 'L', '', 0, '', 'black');
                  break;

               case 'checkbox':
                  if (!empty($elt['custom_values'])) {
                     $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width - 10,
                                                                                             $this->line_height,
                                                                                             Toolbox::decodeFromUtf8($elt['label']),
                                                                                             '', 'L', '', 0, '', 'black');
                     $elt['custom_values']                   = PluginMetademandsField::_unserialize($elt['custom_values']);
                     foreach ($elt['custom_values'] as $id => $label) {
                        $lineNumber[$lineCount][$key] += $this->getMulticellLineNumber($this->activityname_width - 20,
                                                                                       $this->line_height,
                                                                                       Toolbox::decodeFromUtf8($label),
                                                                                       '', 'L', '', 0, '', 'black');
                     }
                  }
                  break;

               case 'radio':
                  if (!empty($elt['custom_values'])) {
                     $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width - 10,
                                                                                             $this->line_height,
                                                                                             Toolbox::decodeFromUtf8($elt['label']),
                                                                                             '', 'L', '', 0, '', 'black');
                     $elt['custom_values']                   = PluginMetademandsField::_unserialize($elt['custom_values']);
                     foreach ($elt['custom_values'] as $id => $label) {
                        $lineNumber[$lineCount][$key] += $this->getMulticellLineNumber($this->activityname_width - 20,
                                                                                       $this->line_height,
                                                                                       Toolbox::decodeFromUtf8($label),
                                                                                       '', 'L', '', 0, '', 'black');
                     }
                  }
                  break;

               case 'title':
                  $lineNumber[$lineCount][$key] = $this->getMulticellLineNumber($this->activityname_width * 4,
                                                                                $this->line_height,
                                                                                Toolbox::decodeFromUtf8($elt['label']),
                                                                                'LRBT', 'C', 'grey', 1, 14, 'black');
                  break;

               case 'linebreak':
                  $lineNumber[$lineCount][$key] = $this->getMulticellLineNumber($this->activityname_width * 4,
                                                                                $this->line_height, 'linebreak', 'LRBT',
                                                                                'C', 'grey', 1, 14, 'black');
                  break;

               case 'datetime_interval':
                  $value                                  = Html::convDate($fields['fields'][$elt['id']]);
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          'LRBT', 'C', 'blue', 1, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($value),
                                                                                          'L', 'L', '', 0, '', 'black');
                  break;

               case 'text':
                  $value                                  = $fields["fields"][$elt['id']];
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          'LRBT', 'C', 'blue', 1, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($value),
                                                                                          'L', 'L', '', 0, '', 'black');
                  break;

               case 'datetime':
                  $value                                  = Html::convDate($fields['fields'][$elt['id']]);
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          'LRBT', 'C', 'blue', 1, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($value),
                                                                                          'L', 'L', '', 0, '', 'black');
                  break;

               case 'dropdown':
                  switch ($elt['item']) {
                     case 'user':
                        $value = $dbu->getUserName($fields['fields'][$elt['id']]);
                        break;

                     case 'location':
                     case 'PluginResourcesResource':
                     case 'group':
                        $value = Dropdown::getDropdownName($dbu->getTableForItemType($elt['item']),
                                                           $fields['fields'][$elt['id']]);
                        break;

                     case 'other':
                        if (!empty($elt['custom_values'])) {
                           $elt['custom_values'] = PluginMetademandsField::_unserialize($elt['custom_values']);
                           $value                = $elt['custom_values'][$fields['fields'][$elt['id']]];
                        }
                        break;
                  }
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          'LRBT', 'C', 'blue', 1, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($value),
                                                                                          'L', 'L', '', 0, '', 'black');
                  break;

               case 'yesno':
                  $value = __('No');
                  if ($fields['fields'][$elt['id']] == 2) {
                     $value = __('Yes');
                  }
                  $lineNumber[$lineCount][$key . 'label'] = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($elt['label']),
                                                                                          'LRBT', 'C', 'blue', 1, '', 'black');
                  $lineNumber[$lineCount][$key]           = $this->getMulticellLineNumber($this->activityname_width,
                                                                                          $this->line_height,
                                                                                          Toolbox::decodeFromUtf8($value),
                                                                                          'L', 'L', '', 0, '', 'black');
                  break;
            }

            $fielCount++;

            if (isset($lastField[$fielCount - 1])) {
               if ($lastField[$fielCount - 1]['type'] != 'title'
                   && $lastField[$fielCount - 1]['type'] != 'datetime_interval'
                   && $lastField[$fielCount - 1]['type'] != 'linebreak') {
                  if ($fielCount == 2) {
                     $fielCount = 0;
                     $lineCount++;
                     $lastField = [];
                  }
               } else {
                  $fielCount = 0;
                  $lineCount++;
                  $lastField = [];
               }
            }
         }
      }

      $fielCount = 0;
      $lineCount = 0;
      $lastField = [];
      $rank      = 1;

      $y = $this->GetY();
      $x = $this->GetX();
      $this->CellValue($this->largeur_grande_cell - (2 * $this->margin_left),
                       $this->hauteurFeuille - 35 - (2 * $this->margin_top), '', 'LRBT', '', '', 0,
                       '', 'black');
      $this->SetY($y);
      $this->SetX($x);

      foreach ($newForm as $key => $elt) {
         if (isset($fields['fields'][$elt['id']]) || $elt['type'] == 'title' || $elt['type'] == 'linebreak') {
            $value                 = null;
            $y                     = $this->GetY();
            $x                     = $this->GetX();
            $lastField[$fielCount] = $elt;

            $max_height   = max($lineNumber[$lineCount]) * $this->line_height;
            $height       = ($max_height / ($lineNumber[$lineCount][$key] * $this->line_height)) * $this->line_height;
            $label_height = ($max_height / ($lineNumber[$lineCount][$key . 'label'] * $this->line_height)) * $this->line_height;

            $valueBorder = 'L';
            if ($fielCount > 0) {
               $valueBorder = 'LR';
            }

            switch ($elt['type']) {
               case 'textarea':
                  $value = $fields["fields"][$elt['id']];
                  // Draw label
                  $this->MultiCellValue($this->activityname_width, $label_height,
                                        Toolbox::decodeFromUtf8($elt['label']),
                                        'LRBT', 'C', 'blue', 1, '', 'black');
                  $this->SetY($y);
                  $this->SetX($x + $this->activityname_width);
                  // Draw line
                  $this->MultiCellValue($this->activityname_width, $height, str_replace(['\r\n', '\n'],
                                                                                        "\n", $value),
                                        $valueBorder, 'L', '', 0, '', 'black');
                  break;

               case 'title':
                  if ($fielCount > 0) {
                     $this->SetY($y + $label_height);
                     $this->SetX($this->margin_left);
                  }
                  // Draw line
                  $this->MultiCellValue($this->activityname_width * 4, $label_height,
                                        Toolbox::decodeFromUtf8($elt['label']), 'LRBT', 'C',
                                        'grey', 1, 14, 'black');
                  $this->Ln(0.2);
                  break;

               case 'linebreak':
                  if ($fielCount > 0) {
                     $this->SetY($y + $this->line_height);
                     $this->SetX($this->margin_left - 0.2);
                  }
                  // Draw line
                  $this->CellValue(($this->activityname_width * 4) + 0.2, $this->line_height, '', 'TB', 'C', '', 0, '',
                                   'black');
                  $this->Ln($this->line_height + 0.2);
                  break;

               case 'text':
                  $value = $fields["fields"][$elt['id']];
                  // Draw label
                  $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                        'LRBT', 'C', 'blue', 1, '', 'black');
                  $this->SetY($y);
                  $this->SetX($x + $this->activityname_width);
                  // Draw line
                  $this->MultiCellValue($this->activityname_width, $height, Toolbox::decodeFromUtf8($value),
                                        $valueBorder, 'L', '', 0, '', 'black');
                  break;

               case 'datetime':
                  $value = Html::convDate($fields['fields'][$elt['id']]);
                  // Draw label
                  $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                        'LRBT', 'C', 'blue', 1, '', 'black');
                  $this->SetY($y);
                  $this->SetX($x + $this->activityname_width);
                  // Draw line
                  $this->MultiCellValue($this->activityname_width, $height, Toolbox::decodeFromUtf8($value),
                                        $valueBorder, 'L', '', 0, '', 'black');
                  break;

               case 'dropdown':
                  switch ($elt['item']) {
                     case 'user':
                        $value = $dbu->getUserName($fields['fields'][$elt['id']]);
                        break;

                     case 'location':
                     case 'PluginResourcesResource':
                     case 'group':
                        $value = Dropdown::getDropdownName($dbu->getTableForItemType($elt['item']),
                                                           $fields['fields'][$elt['id']]);
                        break;

                     case 'other':
                        if (!empty($elt['custom_values'])) {
                           $elt['custom_values'] = PluginMetademandsField::_unserialize($elt['custom_values']);
                           $value                = $elt['custom_values'][$fields['fields'][$elt['id']]];
                        }
                        break;
                  }
                  // Draw label
                  $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                        'LRBT', 'C', 'blue', 1, '', 'black');
                  $this->SetY($y);
                  $this->SetX($x + $this->activityname_width);
                  // Draw line
                  $this->MultiCellValue($this->activityname_width, $height, Toolbox::decodeFromUtf8($value), $valueBorder,
                                        'L', '', 0, '', 'black');
                  break;

               case 'yesno':
                  $value = __('No');
                  if ($fields['fields'][$elt['id']] == 2) {
                     $value = __('Yes');
                  }
                  // Draw label
                  $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                        'LRBT', 'C', 'blue', 1, '', 'black');
                  $this->SetY($y);
                  $this->SetX($x + $this->activityname_width);
                  // Draw line
                  $this->MultiCellValue($this->activityname_width, $height, Toolbox::decodeFromUtf8($value), $valueBorder,
                                        'L', '', 0, '', 'black');
                  break;

               case 'checkbox':
                  if (!empty($elt['custom_values'])) {
                     $elt['custom_values']         = PluginMetademandsField::_unserialize($elt['custom_values']);
                     $fields['fields'][$elt['id']] = PluginMetademandsField::_unserialize($fields['fields'][$elt['id']]);

                     // Draw label
                     $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                           'LRBT', 'C', 'blue', 1, '', 'black');
                     $this->SetY($y + 1);
                     $this->SetX($x + $this->activityname_width);

                     $x  = $this->GetX() + 1;
                     $x2 = $this->GetX() + 6;

                     // Draw line
                     foreach ($elt['custom_values'] as $id => $label) {
                        $this->SetX($x);
                        $this->getCaractereCheckbox(!isset($fields['fields'][$elt['id']][$id]), $this->GetX(),
                                                    $this->GetY());
                        $this->SetX($x2);
                        $this->MultiCellValue($this->activityname_width - 11, $height, Toolbox::decodeFromUtf8($label),
                                              '', 'L', '', 0, '', 'black');
                     }
                  }
                  break;

               case 'radio':
                  if (!empty($elt['custom_values'])) {
                     $elt['custom_values']         = PluginMetademandsField::_unserialize($elt['custom_values']);
                     $fields['fields'][$elt['id']] = PluginMetademandsField::_unserialize($fields['fields'][$elt['id']]);

                     // Draw label
                     $this->MultiCellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                           'LRBT', 'C', 'blue', 1, '', 'black');
                     $this->SetY($y + 1);
                     $this->SetX($x + $this->activityname_width);

                     $x  = $this->GetX() + 1;
                     $x2 = $this->GetX() + 6;

                     // Draw line
                     foreach ($elt['custom_values'] as $id => $label) {
                        $this->SetX($x);
                        $this->getCaractereCheckbox(!isset($fields['fields'][$elt['id']][$id]), $this->GetX(),
                                                    $this->GetY());
                        $this->SetX($x2);
                        $this->MultiCellValue($this->activityname_width - 11, $height, Toolbox::decodeFromUtf8($label),
                                              '', 'L', '', 0, '', 'black');
                     }
                  }
                  break;

               case 'datetime_interval':
                  if ($fielCount > 0) {
                     $this->SetY($y + $label_height);
                     $this->SetX($this->margin_left);
                  }
                  // Draw label
                  $this->CellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label']),
                                   'LRBT', 'C', 'blue', 1, '', 'black');
                  //                  $this->SetX($this->GetX() + $this->activityname_width);
                  // Draw line
                  $this->CellValue($this->activityname_width, $height, Html::convDate($fields['fields'][$elt['id']]),
                                   'LR', 'L', '', 0, '', 'black');

                  // Draw label 2
                  $this->CellValue($this->activityname_width, $label_height, Toolbox::decodeFromUtf8($elt['label2']),
                                   'LRBT', 'C', 'blue', 1, '', 'black');
                  //                  $this->SetX($this->GetX() + ($this->activityname_width));
                  // Draw line 2
                  $this->CellValue($this->activityname_width, $height, Html::convDate($fields['fields'][$elt['id'] . "-2"]), 'LR', 'L', '', 0, '', 'black');
                  $this->Ln($height);
                  break;
            }

            $fielCount++;

            // Handle line break
            if (isset($lastField[$fielCount - 1])) {
               if ($lastField[$fielCount - 1]['type'] != 'title'
                   && $lastField[$fielCount - 1]['type'] != 'datetime_interval'
                   && $lastField[$fielCount - 1]['type'] != 'linebreak') {
                  if ($fielCount >= 2) {
                     $this->SetY($y + $label_height);
                     $this->SetX($this->margin_left);
                     $fielCount = 0;
                     $lineCount++;
                     $lastField = [];

                  } else if ($fielCount > 0) {
                     $this->SetY($y);
                     //TODO
                     $width = ($lastField[$fielCount - 1]['type'] != 'checkbox') ? $x + ($this->activityname_width * 2) : $x + ($this->activityname_width - 1);
                     $this->SetX($width);
                  }

               } else {
                  $fielCount = 0;
                  $lineCount++;
                  $lastField = [];
               }
            }

            $rank = $elt['rank'];
         }
      }
   }

   /**
    * @param        $w
    * @param        $h
    * @param        $txt
    * @param int    $border
    * @param string $align
    * @param int    $fill
    *
    * @return int
    */
   public function getMulticellLineNumber($w, $h, $txt, $border = 0, $align = 'J', $fill = 0) {

      $count = 0;

      //Output text with automatic or explicit line breaks
      $cw = &$this->CurrentFont['cw'];
      if ($w == 0) {
         $w = $this->w - $this->rMargin - $this->x;
      }
      $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
      $s    = str_replace("\r", '', $txt);
      $nb   = strlen($s);
      if ($nb > 0 && $s[$nb - 1] == "\n") {
         $nb--;
      }

      $sep = -1;
      $i   = 0;
      $j   = 0;
      $l   = 0;
      $ns  = 0;
      $nl  = 1;
      while ($i < $nb) {
         //Get next character
         $c = $s{$i};
         if ($c == "\n") {
            $count++;
            $i++;
            $sep = -1;
            $j   = $i;
            $l   = 0;
            $ns  = 0;
            $nl++;
            continue;
         }
         if ($c == ' ') {
            $sep = $i;
            $ns++;
         }
         $l += $cw[$c];
         if ($l > $wmax) {
            //Automatic line break
            if ($sep == -1) {
               if ($i == $j) {
                  $i++;
               }
               $count++;
            } else {
               $count++;
               $i = $sep + 1;
            }
            $sep = -1;
            $j   = $i;
            $l   = 0;
            $ns  = 0;
            $nl++;
         } else {
            $i++;
         }
      }

      $count++;

      return $count;
   }

   /**
    * @param $checked
    * @param $x
    * @param $y
    */
   public function getCaractereCheckbox($checked, $x, $y) {

      return $checked ? $this->Image("../pics/checked.png", $x, $y, 4, 4) :
         $this->Image("../pics/unchecked.png", $x, $y, 4, 4);
   }

   function Header() {
      $this->SetY($this->margin_top);
      $this->SetX($this->margin_left);

      $largeurCoteTitre = 35;
      $largeurCaseTitre = $this->largeur_grande_cell - ($this->margin_left * 2) - ($largeurCoteTitre * 2);

      //Cellule contenant l'image
      $largeurImage  = 30;
      $hauteurImage  = 9;
      $abscisseImage = ($largeurCoteTitre - $largeurImage) / 2 + $this->margin_left;
      $ordonneeImage = (10 - $hauteurImage) / 2 + $this->margin_top;
      $this->CellValue($largeurCoteTitre, 20, '', 'TBL', 'L', '', 0, '', 'black');
      $this->Image("../pics/logo.jpg", $abscisseImage, $ordonneeImage, $largeurImage, $hauteurImage);

      //Cellule contenant le titre
      $this->SetX($this->margin_left + $largeurCoteTitre);
      $this->CellValue($largeurCaseTitre, 20, $this->titre, 'TBL', 'C', '', 1, '', 'black');

      //Cellule ne contenant rien pour le moment
      $this->SetX($this->margin_left + $largeurCoteTitre + $largeurCaseTitre);
      $this->CellValue($largeurCoteTitre, 5, __('Created on', 'metademands'), 'TLR', 'C', '', 0, '', 'black');
      $this->SetY($this->GetY() + 5);
      $this->SetX($this->margin_left + $largeurCoteTitre + $largeurCaseTitre);
      $this->CellValue($largeurCoteTitre, 5, Html::convDate(date('Y-m-d')), 'LR', 'C', '', 0, '', 'black');
      $this->SetY($this->GetY() + 5);
      $this->SetX($this->margin_left + $largeurCoteTitre + $largeurCaseTitre);
      $this->CellValue($largeurCoteTitre, 10, '', 'BLR', 'C', '', 0, '', 'black');
      $this->SetY($this->GetY() + 15);
   }

   /**
    * @param $form
    * @param $fields
    */
   public function drawPdf($form, $fields) {

      $this->AliasNbPages();
      $this->AddPage();

      $this->setFields($form, $fields);
   }

   function Footer() {
      $this->SetY($this->hauteurFeuille - $this->margin_top - $this->hauteurPiedDePage);
      $numeroPage = $this->PageNo();
      $this->Cell($this->largeurPage, $this->hauteurPiedDePage, $numeroPage . " sur {nb}", 0, 0, 'C');
   }

   /**
    * @param $idTicket
    * @param $entitiesId
    *
    * @return \Document_Item
    */
   public function addDocument($idTicket, $entitiesId) {
      //Construction du chemin du fichier
      $this->Output(GLPI_DOC_DIR . "/_uploads/metademand_" . $idTicket . ".pdf");
      $filename = "metademand_" . $idTicket . ".pdf";
      //Création du document
      $doc = new Document();
      //Construction des données
      $input                = [];
      $input["name"]        = "/" . addslashes($filename);
      $input["upload_file"] = "/" . $filename;
      $input["mime"]        = "application/pdf";
      $input["date_mod"]    = date("Y-m-d H:i:s");
      $input["users_id"]    = Session::getLoginUserID();
      $input["entities_id"] = $entitiesId;
      $input["tickets_id"]  = $idTicket;
      //entities_id
      //tickets_id
      //Initialisation du document
      $newdoc  = $doc->add($input);
      $docitem = new Document_Item();

      //entities_id
      $docitem->add([
                       'itemtype'     => "Ticket",
                       "documents_id" => $newdoc,
                       "items_id"     => $idTicket,
                       "entities_id"  => $entitiesId]
      );
      return $docitem;
   }

}
