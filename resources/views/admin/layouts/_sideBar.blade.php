        @php        
        SidebarMenu::add('<span>'.__('admincommon.Dashboard').'</span>', 'admin-dashboard', 'dashboard');
        
        $appSettings = SidebarMenu::add('<span>'.__('admincrud.App Settings').'</span>', '#', 'cog');
        SidebarMenu::addSub($appSettings, __('admincrud.App Configuration'), 'admin-app-settings', 'App Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.Mail Configuration'),'admin-mail-settings', 'Mail Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.SMS Configuration'),'admin-sms-settings', 'SMS Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.Social Media Configuration'),'admin-social-media-settings', 'Social Media Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.Currency Configuration'),'admin-currency-settings', 'Currency Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.Delivery boy Configuration'),'admin-delivery-boy-settings', 'Deliveryboy Settings');
        SidebarMenu::addSub($appSettings, __('admincrud.Banner Management'), 'banner.index', 'flag');
        

        $loyaltPointManagement = SidebarMenu::add('<span>'.__('admincrud.Loyalty Point Management').'</span>', '#', 'user-secret');    
        SidebarMenu::addSub($loyaltPointManagement, __('admincrud.Loyalty Point Management'), 'loyaltypoint.index', 'flag');
        /*SidebarMenu::addSub($loyaltPointManagement, __('admincrud.Loyalty Redeem Configuration'),'admin-loyalty-point-settings', 'Loyalty Point Settings'); */ 
        SidebarMenu::addSub($loyaltPointManagement, __('admincrud.Loyalty Level Management'), 'loyaltylevel.index', 'flag');      

        $addressManagement = SidebarMenu::add('<span>'.__('admincrud.Address Management').'</span>', '#', 'map-marker');
        SidebarMenu::addSub($addressManagement, __('admincrud.Address Type Management'), 'addresstype.index', 'Address Type Management');
        SidebarMenu::addSub($addressManagement, __('admincrud.Country Management'), 'country.index','City Management');
        SidebarMenu::addSub($addressManagement, __('admincrud.City Management'), 'city.index', 'City Management');
        SidebarMenu::addSub($addressManagement, __('admincrud.Area Management'), 'area.index', 'Area Management');

        $itemManagement = SidebarMenu::add('<span>'.__('admincrud.Item Management').'</span>', '#', 'cutlery');
        SidebarMenu::addSub($itemManagement, __('admincrud.Category Management'), 'category.index', 'flag');
        SidebarMenu::addSub($itemManagement, __('admincrud.Cuisine Management'), 'cuisine.index', 'cutlery');
        SidebarMenu::addSub($itemManagement, __('admincrud.Ingredient Management'), 'ingredient.index', 'flag');
        SidebarMenu::addSub($itemManagement, __('admincrud.Ingredient Group Management'), 'ingredient-group.index', 'flag');
        SidebarMenu::addSub($itemManagement, __('admincrud.Item Management'), 'item.index', 'flag');
        
        if(APP_GUARD !== GUARD_VENDOR) {
            $vendorManagement = SidebarMenu::add('<span>'.__('admincrud.Vendor Management').'</span>', '#', 'user-secret');    
            SidebarMenu::addSub($vendorManagement, __('admincrud.Vendor Management'), 'vendor.index', 'flag');
        }

        $branchManagement = SidebarMenu::add('<span>'.__('admincrud.Branch Management').'</span>', '#', 'cutlery');    
        SidebarMenu::addSub($branchManagement, __('admincrud.Branch Management'), 'branch.index', 'flag');
        
        $adminUser = SidebarMenu::add('<span>'.__('admincrud.User Management').'</span>', '#', 'user-plus');
        SidebarMenu::addSub($adminUser, __('admincrud.Customer Management'), 'user.index', 'dashboard');
        SidebarMenu::addSub($adminUser, __('admincrud.User Address Management'), 'useraddress.index', 'dashboard');
        SidebarMenu::addSub($adminUser, __('admincrud.User Wishlist Management'), 'userwishlist.index', 'dashboard');
        SidebarMenu::addSub($adminUser, __('admincrud.Admin User Management'), 'admin-user.index', 'dashboard');
        SidebarMenu::addSub($adminUser, __('admincrud.Role Management'), 'role.index', 'flag');
                        
        $deliveryBoy = SidebarMenu::add('<span>'.__('admincrud.Delivery Boy Management').'</span>', '#', 'bicycle');
        SidebarMenu::addSub($deliveryBoy, __('admincrud.Delivery Area Management'), 'delivery-area.index', 'flag');
        SidebarMenu::addSub($deliveryBoy, __('admincrud.Delivery Boy Management'), 'deliveryboy.index', 'flag');
        SidebarMenu::addSub($deliveryBoy, __('admincrud.Delivery Charge Management'), 'deliverycharge.index', 'flag');


        $orderManagement = SidebarMenu::add('<span>'.__('admincrud.Order Management').'</span>', '#', 'shopping-cart');
        SidebarMenu::addSub($orderManagement, __('admincrud.Order Management'), 'order.index', 'flag');

        $corporateOrderManagement = SidebarMenu::add('<span>'.__('admincrud.Corporate Order Management').'</span>', '#', 'shopping-cart');
        SidebarMenu::addSub($corporateOrderManagement, __('admincrud.Corporate Order Management'), 'corporate-order.index', 'flag');

        $reportManagement = SidebarMenu::add('<span>'.__('admincrud.Report Management').'</span>', '#', 'file');
        SidebarMenu::addSub($reportManagement, __('admincrud.Report Management'), 'report.index', 'flag');
        
        $ratingManagement = SidebarMenu::add('<span>'.__('admincrud.Ratings Management').'</span>', '#', 'star');
        SidebarMenu::addSub($ratingManagement, __('admincrud.Ratings Management'), 'review.index', 'flag');

        $offerManagement = SidebarMenu::add('<span>'.__('admincrud.Offer Management').'</span>', '#', 'gift');
        SidebarMenu::addSub($offerManagement, __('admincrud.Offer Management'), 'offer.index', 'flag');

        $corporateOfferManagement = SidebarMenu::add('<span>'.__('admincrud.Corporate Offer Management').'</span>', '#', 'gift');
        SidebarMenu::addSub($corporateOfferManagement, __('admincrud.Corporate Voucher Terms'),'admin-corporate-settings', 'Corporate Settings');
        SidebarMenu::addSub($corporateOfferManagement, __('admincrud.Corporate Offer Management'), 'corporate-offer.index', 'flag');
        

        $paymentManagement = SidebarMenu::add('<span>'.__('admincrud.Payment Management').'</span>', '#', 'gift');
        SidebarMenu::addSub($paymentManagement, __('admincrud.Payment Management'), 'vendorpayment.index', 'flag');

       /*  $loyaltLevelManagement = SidebarMenu::add('<span>'.__('admincrud.Loyalty Level Management').'</span>', '#', 'user-secret');    
        SidebarMenu::addSub($loyaltLevelManagement, __('admincrud.Loyalty Level Management'), 'loyaltylevel.index', 'flag');     */

        SidebarMenu::add('<span>'.__('admincrud.Voucher Management').'</span>', 'voucher.index', 'gift');

        SidebarMenu::add('<span>'.__('admincrud.Enquiry Management').'</span>', 'enquiry.index', 'envelope-open-o');

        $newsLetter = SidebarMenu::add('<span>'.__('admincrud.Newsletter Management').'</span>', '#', 'newspaper-o');
        SidebarMenu::addSub($newsLetter, __('admincrud.Newsletter Management'), 'newsletter.index', 'flag');
        SidebarMenu::addSub($newsLetter, __('admincrud.Newsletter Subscriber Management'), 'newsletter-subscriber.index', 'flag');
        
        SidebarMenu::add('<span>'.__('admincrud.CMS Management').'</span>', 'cms.index', 'file-text-o');
        SidebarMenu::add('<span>'.__('admincrud.FAQ Management').'</span>', 'faq.index', 'question-circle');            
        SidebarMenu::add('<span>'.__('admincrud.Activity Log').'</span>', 'activity-log.index', 'history');            


        SidebarMenu::render();

        @endphp   
