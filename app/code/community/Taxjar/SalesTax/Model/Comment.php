<?php
class Taxjar_SalesTax_Model_Comment{
    public function getCommentText(){ //this method must exits. It returns the text for the comment
      $regionId       = Mage::getStoreConfig('shipping/origin/region_id');
      $regionCode     = Mage::getModel('directory/region')->load($regionId)->getCode();
      $lastUpdate     = Mage::getStoreConfig('taxjar/config/last_update');
      $states         = unserialize(Mage::getStoreConfig('taxjar/config/states'));
      $states[]       = $regionCode;
      sort($states);
      foreach (array_unique($states) as $state) {
        if ( ( $stateName = $this->fullStateName($state) ) && ! empty($stateName) ){
          $statesHtml .= '<li class="success-msg"><ul><li><span style="font-size: 1.4em;">' . $stateName . '</span></li></ul></li>';
        }
      };
      if($lastUpdate){
        return "<br/><p>TaxJar has <em>automatically</em> added rates for the following states to your Magento installation:<br/><ul class='messages'>". $statesHtml . "</ul>To manage your TaxJar states <a href='https://app.taxjar.com/account#states'  target='_blank'>click here</a>.</p><p>Your sales tax rates were last updated on: <ul class='messages'><li class='info-msg'><ul><li><span style='font-size: 1.4em;'>" . $lastUpdate . "</span></li></ul></li></ul></p><p>If you would like to uninstall TaxJar, remove the API Token from the box above, then save the config.  This will remove all the rates.  You can then uninstall in the Magento Connect Manager.</p>";
      } else {
        $regionName = fullStateName($regionCode);
        return "<br/><p>Enter your TaxJar API Token to import current sales tax rates for all zip codes in " . $regionName . ", your state of origin as set in Shipping Settings. We will also retrieve all other states from your TaxJar account. To get an API Token, go to <a href='https://app.taxjar.com/account' target='_blank'>TaxJar's Account Screen.</a></p><p>For more information on how your tax settings are changed, <a href='http://taxjar.com/magento/tax-settings' target='_blank'>click here</a>.</p>";
    }
  }

  private function fullStateName($stateCode) {
    $regionModel = Mage::getModel('directory/region')->loadByCode($stateCode, 'US');
    return $regionModel->getDefaultName();
  }
}
?>
