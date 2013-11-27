<?php
/**
 * @copyright 4ward.media 2013 <http://www.4wardmedia.de>
 * @author christoph wiechert <wio@psitrax.de>
 */

if(version_compare(VERSION, '3.2', '<')) return;


\Database\Updater::convertSingleField('tl_news4ward_article', 'teaserImage');
\Database\Updater::convertSingleField('tl_news4ward_article', 'facebookImage');
\Database\Updater::convertSingleField('tl_news4ward', 'filePath');

