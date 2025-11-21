<?php

namespace Database\Seeders\Grocery;

use App\Models\CmsPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\{Storage, File};
use Illuminate\Support\Facades\DB;

class CmsPageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    /* DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Schema::disableForeignKeyConstraints();
    CmsPage::truncate();
    Schema::enableForeignKeyConstraints(); */

    $now = date('Y-m-d H:i:s');
    $cmsPageData = [
      [
        'id' => 1,
        'title' => 'Privacy Policy',
        'body' => '<p>At Mayuri, your privacy is very important to us. This Privacy Policy outlines how we collect, use, and protect your personal information when you visit or make a purchase from our website, <a href="javascript: void(0);">Mayuri.com</a>.</p><h3>Information We Collect</h3><p>We collect the following types of information:</p><ul><li><strong>Personal Information:</strong> Name, email address, phone number, billing/shipping address, and payment details.</li><li><strong>Account Information:</strong> If you create an account, we collect login credentials and order history.</li><li><strong>Browsing Information:</strong> IP address, browser type, device information, pages visited, and cookies.</li></ul><h3>How We Use Your Information</h3><p>We use the information we collect to:</p><ul><li>Process your orders and manage your account.</li><li>Communicate with you about orders, products, and promotions.</li><li>Improve our website, services, and customer experience.</li><li>Detect and prevent fraud or abuse.</li></ul><h3>Sharing Your Information</h3><p>We do not sell your personal information. However, we may share data with:</p><ul><li>Third-party service providers (e.g., payment processors, shipping companies).</li><li>Legal authorities when required by law.</li><li>Analytics and marketing partners (e.g., Google Analytics, Meta Pixel) to help improve our services.</li></ul><h3>Cookies and Tracking Technologies</h3><p>We use cookies and similar technologies to enhance your experience. You can disable cookies through your browser settings, but some features of the site may not function properly.</p><h3>Your Rights</h3><p>You have the right to:</p><p>Access, update, or delete your personal data. Opt out of marketing communications. Request data portability.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your information. However, no method of transmission over the internet is 100% secure.</p>',
        'slug' => 'privacy-policy',
        'feature_image' => 'privacy-banner.webp',
        'meta_title' => null,
        'meta_keywords' => null,
        'meta_description' => null,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null
      ],
      [
        'id' => 2,
        'title' => 'Terms Of Use',
        'body' => '<p>At Mayuri, your privacy is extremely important to us. This Privacy Policy outlines how we collect, use, disclose, and safeguard your personal information when you visit, interact with, or make a purchase from our website, <a href="javascript: void(0);">Mayuri.com</a>. We are committed to ensuring that your personal data is handled securely and in compliance with all applicable data protection laws and regulations.</p><h3>Information We Collect</h3><p>We collect the following types of information:</p><ul><li><strong>Personal Information:</strong> Name, email address, phone number, billing/shipping address, and payment details.</li><li><strong>Account Information:</strong> If you create an account, we collect login credentials and order history.</li><li><strong>Browsing Information:</strong> IP address, browser type, device information, pages visited, and cookies.</li></ul><h3>How We Use Your Information</h3><p>We use the information we collect to:</p><ul><li>Process your orders and manage your account.</li><li>Communicate with you about orders, products, and promotions.</li><li>Improve our website, services, and customer experience.</li><li>Detect and prevent fraud or abuse.</li></ul><h3>Sharing Your Information</h3><p>We do not sell your personal information. However, we may share data with:</p><ul><li>Third-party service providers (e.g., payment processors, shipping companies).</li><li>Legal authorities when required by law.</li><li>Analytics and marketing partners (e.g., Google Analytics, Meta Pixel) to help improve our services.</li></ul><h3>Cookies and Tracking Technologies</h3><p>We use cookies and similar technologies to enhance your experience. You can disable cookies through your browser settings, but some features of the site may not function properly.</p><h3>Your Rights</h3><p>You have the right to:</p><p>Access, update, or delete your personal data. Opt out of marketing communications. Request data portability.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your information. However, no method of transmission over the internet is 100% secure.</p>',
        'slug' => 'terms-of-use',
        'feature_image' => 'terms-banner.webp',
        'meta_title' => null,
        'meta_keywords' => null,
        'meta_description' => null,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null
      ],
      [
        'id' => 3,
        'title' => 'About Us',
        'body' => null,
        'slug' => 'about-us',
        'feature_image' => 'about-banner.webp',
        'meta_title' => null,
        'meta_keywords' => null,
        'meta_description' => null,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null
      ],
      [
        'id' => 4,
        'title' => 'FAQS',
        'body' => '<h3>Frequently Asked Questions (FAQs)</h3><h4>1. What payment methods do you accept?</h4><p>We accept all major credit and debit cards (Visa, MasterCard, RuPay), net banking, UPI, wallets, and COD (Cash on Delivery) for eligible orders.</p><h4>2. How long will it take to receive my order?</h4><p>Delivery times vary depending on your location and the product. Typically, orders are delivered within <strong>5–10 business days</strong>. You’ll receive tracking details once your order is shipped.</p><h4>3. Can I track my order?</h4><p>Yes, once your order is shipped, you’ll receive a tracking number via email or SMS. You can use it to track your order in real time.</p><h4>4. Do you offer free shipping?</h4><p>We offer free shipping on most orders above a certain value. Shipping charges (if applicable) are displayed at checkout.</p><h4>5. What is your return policy?</h4><p>We offer a <strong>7-day return window</strong> for eligible products. Items must be unused, in original packaging, and meet our return conditions. Please read our <a href="' . route('home') . '">Return Policy</a> for details.</p><h4>6. How can I cancel my order?</h4><p>You can cancel your order within 24 hours of placing it, as long as it hasn’t been shipped. Please contact our support team for assistance.</p><h4>7. What if I receive a damaged or wrong item?</h4><p>If your product arrives damaged or incorrect, notify us within 48 hours with photos. We’ll arrange a replacement or refund as soon as possible.</p><h4>8. Do I need to create an account to place an order?</h4><p>No, you can place an order as a guest. However, creating an account allows you to view order history, track shipments, and save addresses for faster checkout.</p><h4>9. Do you offer bulk or corporate orders?</h4><p>Yes! For bulk or corporate orders, please email us at <a href=\"mailto:sales@living.com\">sales@living.com</a> and our team will assist you.</p><h4>10. How can I contact customer support?</h4><p>You can reach us via email at <a href=\"mailto:support@living.com\">support@living.com</a> or call our helpline at <strong>1800-123-456</strong> (Mon–Sat, 10 AM – 6 PM).</p>',
        'slug' => 'faqs',
        'feature_image' => null,
        'meta_title' => null,
        'meta_keywords' => null,
        'meta_description' => null,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => null,
        'deleted_by' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null
      ],
      [
        'id' => 5,
        'title' => 'Return Policy',
        'body' => '<h3>Return &amp; Refund Policy</h3><p>At Mayuri, we want you to love your purchase. If you\'re not completely satisfied, we’re here to help.</p><h3>Eligibility for Returns</h3><p>You may return most new, unused items within <strong>7 days of delivery</strong> for a full refund or exchange, subject to the following conditions:</p><ul><li>The item must be unused, unassembled, and in its original packaging.</li><li>Return requests must be initiated within 7 days of receiving the product.</li><li>Items marked as final sale, clearance, or customized products are non-returnable.</li></ul><h3>How to Initiate a Return</h3><p>To start a return, please contact our customer support team at <a href=\"mailto:support@living.com\">support@living.com</a> with your order number and reason for return. We’ll provide instructions and a return shipping label (if applicable).</p><h3>Return Shipping</h3><ul><li>If the return is due to our error (e.g., defective or incorrect item), we’ll cover return shipping costs.</li><li>For all other returns, the customer is responsible for return shipping.</li></ul><h3>Refund Process</h3><p>Once we receive and inspect your returned item, your refund will be processed within <strong>5–7 business days</strong>. Refunds will be issued to your original payment method.</p><h3>Damaged or Defective Items</h3><p>If your order arrives damaged or defective, please contact us within <strong>48 hours</strong> of delivery with photos and a description. We\'ll arrange a replacement or refund as quickly as possible.</p><h3>Exchanges</h3><p>We offer exchanges for eligible items. Contact us to initiate an exchange request and confirm item availability.</p><h3>Contact Us</h3><p>If you have any questions about your return, feel free to reach out to our support team at <a href=\"mailto:support@living.com\">support@living.com</a>.</p>',
        'slug' => 'return-policy',
        'feature_image' => 'return-policy-banner.webp',
        'meta_title' => NULL,
        'meta_keywords' => NULL,
        'meta_description' => NULL,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => NULL,
        'deleted_by' => NULL,
        'created_at' => '2025-06-11 05:49:06',
        'updated_at' => '2025-06-11 06:17:29',
        'deleted_at' => NULL
      ],
      [
        'id' => 6,
        'title' => 'Contact Us',
        'body' => NULL,
        'slug' => 'contact-us',
        'feature_image' => NULL,
        'meta_title' => NULL,
        'meta_keywords' => NULL,
        'meta_description' => NULL,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => 6,
        'deleted_by' => NULL,
        'created_at' => '2025-07-17 22:52:48',
        'updated_at' => '2025-07-20 23:14:44',
        'deleted_at' => NULL
      ],

    ];
    CmsPage::insert($cmsPageData);

    $this->uploadBannerImages();
  }

  private function uploadBannerImages()
  {
    // Ensure destination directory exists
    // Storage::makeDirectory('public/uploads/cms_pages');
    Storage::disk('public')->makeDirectory('uploads/cms_pages');

    $destinationPath = storage_path('app/public/uploads/cms_pages');
    $sourcePath = public_path('SeederImages/Grocery/cms_banners');


    // Remove all files in destination folder
    if (File::exists($destinationPath)) {
      File::deleteDirectory($destinationPath);
      Storage::disk('public')->makeDirectory('uploads/cms_pages');
    }

    //  Copy files from source to destination
    if (File::exists($sourcePath)) {
      $files = File::files($sourcePath);

      foreach ($files as $file) {
        File::copy($file->getPathname(), $destinationPath . '/' . $file->getFilename());
      }
    }
  }
}
