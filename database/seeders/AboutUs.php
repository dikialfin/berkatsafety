<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AboutUs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = $this->menu();
        foreach($menu as $val) {
            $about = DB::table('about_us')->where('slug', $val->slug)->first();
            if (empty($about)) {
                DB::table('about_us')->insert([
                    'name' => $val->name,
                    'slug' => $val->slug,
                    'description_id' => $val->content_id,
                    'description_en' => $val->content_en,
                    'sort' => 0,
                    'keyword_id' => $val->name,
                    'keyword_en' => $val->name,
                    'meta_title_id' => $val->name,
                    'meta_title_en' => $val->name,
                    'meta_description_id' => $val->name,
                    'meta_description_en' => $val->name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    private function menu()
    {
        return [
            (object)['slug' => 'about-us', 'name' => 'About Us', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">About Berkat Safety</h1>
                        <p class="mb-2">Committed to Delivering Trusted Safety Solutions for Every Industry.</p>
                        <iframe class="w-100" style="border-radius: 20px; height:375px;" src="https://www.youtube.com/embed/1ouJfAvCLUE?si=fGpSyQXd2j6PiNgp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-3 mb-3">
                        <img src="'.url('/').'/images/about-1.webp" alt="">
                    </div>
                    <div class="col-6 col-sm-3 mb-3">
                        <img src="'.url('/').'/images/about-2.webp" alt="">
                    </div>
                    <div class="col-6 col-sm-3 mb-3">
                        <img src="'.url('/').'/images/about-3.webp" alt="">
                    </div>
                    <div class="col-6 col-sm-3 mb-3">
                        <img src="'.url('/').'/images/about-4.webp" alt="">
                    </div>
                    <div class="col-12 mt-4">
                        <p>
                            Berkat Safety is a leading safety equipment supplier in Indonesia focusing on providing high-performance and innovative PPE products & services.</p>

                        <p>For over 50 years, we have been providing safety solutions to a multitude of industries
                            spanning from oil and gas, mining, construction, firefighter, automotive, utilities, electric,
                            chemical, machinery and equipment, military, and other general & heavy-duty industries
                            & services.</p>

                        <p>Berkat Safety provides a comprehensive range of safety & PPE products. From heavy-duty & welding helmets, ultra-lightweight polycarbonate safety glasses, respiratory protection,
                            Kevlar & chemical gloves, heavy-duty & protective workwear, aramid firefighter suit, safety
                            vest, rescue winch, emergency shower, to eyewash fountain, and other safety & PPE
                            accessories.</p>

                        <p>We help industries to protect their people from the risk they are exposed to, give confidence and peace of mind that they are well protected while performing their works. Creativity & innovation have been the consistent hallmarks of our company. We unceasingly take product development to new levels in order to provide exceptional protection for those who risk their lives every day. Products are specially designed from the inside out, not only to ensure the safety of the user but also comfortable to wear, user-friendly, yet appealing to style-conscious people. Berkat Safety is partnering with safety solution industry leaders from all over the world to ensure that our customers only receive the best quality products available in the market. As a customer-focused organization, Berkat Safety embraces the digital era. We invest heavily in ERP systems to ensure that all products are processed and delivered to our customers on time. At Berkat Safety, we do whatever it takes to live by our commitment to delivering top-notch safety products and services for our clients.
                        </p>
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">About Berkat Safety</h1>
                            <p class="mb-2">Committed to Delivering Trusted Safety Solutions for Every Industry.</p>
                            <iframe class="w-100" style="border-radius: 20px; height:375px;" src="https://www.youtube.com/embed/1ouJfAvCLUE?si=fGpSyQXd2j6PiNgp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-3 mb-3">
                            <img src="'.url('/').'/images/about-1.webp" alt="">
                        </div>
                        <div class="col-6 col-sm-3 mb-3">
                            <img src="'.url('/').'/images/about-2.webp" alt="">
                        </div>
                        <div class="col-6 col-sm-3 mb-3">
                            <img src="'.url('/').'/images/about-3.webp" alt="">
                        </div>
                        <div class="col-6 col-sm-3 mb-3">
                            <img src="'.url('/').'/images/about-4.webp" alt="">
                        </div>
                        <div class="col-12 mt-4">
                            <p>
                                Berkat Safety is a leading safety equipment supplier in Indonesia focusing on providing high-performance and innovative PPE products & services.</p>

                            <p>For over 50 years, we have been providing safety solutions to a multitude of industries
                                spanning from oil and gas, mining, construction, firefighter, automotive, utilities, electric,
                                chemical, machinery and equipment, military, and other general & heavy-duty industries
                                & services.</p>

                            <p>Berkat Safety provides a comprehensive range of safety & PPE products. From heavy-duty & welding helmets, ultra-lightweight polycarbonate safety glasses, respiratory protection,
                                Kevlar & chemical gloves, heavy-duty & protective workwear, aramid firefighter suit, safety
                                vest, rescue winch, emergency shower, to eyewash fountain, and other safety & PPE
                                accessories.</p>

                            <p>We help industries to protect their people from the risk they are exposed to, give confidence and peace of mind that they are well protected while performing their works. Creativity & innovation have been the consistent hallmarks of our company. We unceasingly take product development to new levels in order to provide exceptional protection for those who risk their lives every day. Products are specially designed from the inside out, not only to ensure the safety of the user but also comfortable to wear, user-friendly, yet appealing to style-conscious people. Berkat Safety is partnering with safety solution industry leaders from all over the world to ensure that our customers only receive the best quality products available in the market. As a customer-focused organization, Berkat Safety embraces the digital era. We invest heavily in ERP systems to ensure that all products are processed and delivered to our customers on time. At Berkat Safety, we do whatever it takes to live by our commitment to delivering top-notch safety products and services for our clients.
                            </p>
                        </div>
                    </div>
                '],
            (object)['slug' => 'vision-and-mission', 'name' => 'Vision & Mission', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Vision & Mission</h1>
                        <p class="mb-2">Guided by a clear vision and mission to protect lives and enhance industries.</p>
                        <div class="card border border-primary mt-4">
                            <div class="card-header bg-primary">
                                <h1 class="mb-0 text-white">Our Vision</h1>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-vision.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">To become the biggest PPE supplier in Indonesia, providing innovative, high-quality, and comprehensive product ranges and services.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-primary mt-4">
                            <div class="card-header bg-primary">
                                <h1 class="mb-0 text-white">Our Mission</h1>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-mission-1.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">To provide high-performance and reliable PPE products that ensure the safety of the user.</p>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-mission-2.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">To be the first choice for our clients.</p>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-mission-3.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">Adhere to professional practices and Good Corporate Governance (GCG) principles.</p>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-mission-4.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">To team up with the business partners to develop innovative products and solution that make the workplace safer and better.</p>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-about-us">
                                        <img src="'.url('/').'/images/icon-mission-5.png" alt="">
                                    </div>
                                    <p class="ms-3 mb-0">To foster a working environment that provides an opportunity for personal growth and maximize the potential for the best benefit of both employee and company.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">Vision & Mission</h1>
                            <p class="mb-2">Guided by a clear vision and mission to protect lives and enhance industries.</p>
                            <div class="card border border-primary mt-4">
                                <div class="card-header bg-primary">
                                    <h1 class="mb-0 text-white">Our Vision</h1>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-vision.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">To become the biggest PPE supplier in Indonesia, providing innovative, high-quality, and comprehensive product ranges and services.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card border border-primary mt-4">
                                <div class="card-header bg-primary">
                                    <h1 class="mb-0 text-white">Our Mission</h1>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-mission-1.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">To provide high-performance and reliable PPE products that ensure the safety of the user.</p>
                                    </div>

                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-mission-2.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">To be the first choice for our clients.</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-mission-3.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">Adhere to professional practices and Good Corporate Governance (GCG) principles.</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-mission-4.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">To team up with the business partners to develop innovative products and solution that make the workplace safer and better.</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-about-us">
                                            <img src="'.url('/').'/images/icon-mission-5.png" alt="">
                                        </div>
                                        <p class="ms-3 mb-0">To foster a working environment that provides an opportunity for personal growth and maximize the potential for the best benefit of both employee and company.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                '],
            (object)['slug' => 'our-values', 'name' => 'Our Values', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">LIVING OUR VALUES</h1>
                        <p class="mb-4">In Berkat Safety, doing business ethically and with integrity is the foundation of our company’s culture.</p>
                        <p class="mb-2">Our values shape every decision we make and every product we deliver. They reflect our commitment to safety, integrity, and service excellence, guiding us as we protect and empower businesses worldwide.</p>
                        <img src="'.url('/').'/images/image-berkat.webp" alt="">
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">LIVING OUR VALUES</h1>
                            <p class="mb-4">In Berkat Safety, doing business ethically and with integrity is the foundation of our company’s culture.</p>
                            <p class="mb-2">Our values shape every decision we make and every product we deliver. They reflect our commitment to safety, integrity, and service excellence, guiding us as we protect and empower businesses worldwide.</p>
                            <img src="'.url('/').'/images/image-berkat.webp" alt="">
                        </div>
                    </div>
                '],
            (object)['slug' => 'why-berkat-safety', 'name' => 'Why Berkat Safety?', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Wht Berkat Safety?</h1>
                        <p class="mb-4">Discover what makes Berkat Safety the preferred choice for workplace safety.</p>
                        <p class="mb-2">At Berkat Safety, we understand that workplace safety is non-negotiable. As a trusted provider of high-quality personal protective equipment (PPE) and industrial safety solutions, we are committed to helping businesses create secure and productive environments for their workforce. Here\'s why professionals across industries choose us:</p>
                        <img src="'.url('/').'/images/image-berkat-1.webp" alt="">
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">Wht Berkat Safety?</h1>
                            <p class="mb-4">Discover what makes Berkat Safety the preferred choice for workplace safety.</p>
                            <p class="mb-2">At Berkat Safety, we understand that workplace safety is non-negotiable. As a trusted provider of high-quality personal protective equipment (PPE) and industrial safety solutions, we are committed to helping businesses create secure and productive environments for their workforce. Here\'s why professionals across industries choose us:</p>
                            <img src="'.url('/').'/images/image-berkat-1.webp" alt="">
                        </div>
                    </div>
                '],
            (object)['slug' => 'our-journey', 'name' => 'Our Journey', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">50 YEARS OF ENTREPRENEURIAL EVOLUTION</h1>
                        <p class="mb-4">From Humble Beginnings to Leading Safety Solutions Provider.</p>
                        <p class="mb-2">For over half a century, our journey has been defined by growth, innovation, and a relentless pursuit of excellence. From humble beginnings to becoming a trusted leader in safety solutions, Berkat Safety’s story is one of vision, determination, and unwavering commitment to protecting industries and empowering businesses.</p>
                        <img src="'.url('/').'/images/the-milestone.webp" alt="" class="w-100">
                    </div>
                </div>
                ', 'content_en' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">50 YEARS OF ENTREPRENEURIAL EVOLUTION</h1>
                        <p class="mb-4">From Humble Beginnings to Leading Safety Solutions Provider.</p>
                        <p class="mb-2">For over half a century, our journey has been defined by growth, innovation, and a relentless pursuit of excellence. From humble beginnings to becoming a trusted leader in safety solutions, Berkat Safety’s story is one of vision, determination, and unwavering commitment to protecting industries and empowering businesses.</p>
                        <img src="'.url('/').'/images/the-milestone.webp" alt="" class="w-100">
                    </div>
                </div>
                '],
            (object)['slug' => 'csr', 'name' => 'CSR', 'content_id' => '<div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Corporate Social Responsibility</h1>
                        <p class="mb-4">Building safer, healthier, and more sustainable communities together</p>
                        <p class="mb-3">Our responsibility goes beyond delivering quality safety solutions. We are deeply committed to making a positive impact on the communities we serve and fostering sustainable practices that benefit society and the environment.</p>
                    </div>
                </div>', 'content_en' => '<div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Corporate Social Responsibility</h1>
                        <p class="mb-4">Building safer, healthier, and more sustainable communities together</p>
                        <p class="mb-3">Our responsibility goes beyond delivering quality safety solutions. We are deeply committed to making a positive impact on the communities we serve and fostering sustainable practices that benefit society and the environment.</p>
                    </div>
                </div>'],
            (object)['slug' => 'industry-solution', 'name' => 'Industry Solution', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Industry Solution</h1>
                        <p class="mb-4">Tailored Safety Equipment & Solutions for Every Industry’s Needs.</p>
                        <p class="mb-2">Berkat Safety has established a strong reputation as a leader in the safety &amp; PPE industry, delivering high-quality, innovative solutions.</p>
                        <img src="'.url('/').'/images/industry-solution.webp" class="w-100" alt="Industry Solution">
                    </div>
                </div>
                ', 'content_en' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Industry Solution</h1>
                        <p class="mb-4">Tailored Safety Equipment & Solutions for Every Industry’s Needs.</p>
                        <p class="mb-2">Berkat Safety has established a strong reputation as a leader in the safety &amp; PPE industry, delivering high-quality, innovative solutions.</p>
                        <img src="'.url('/').'/images/industry-solution.webp" class="w-100" alt="Industry Solution">
                    </div>
                </div>
                '],
            (object)['slug' => 'integrated-system', 'name' => 'Integrated System', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Integrated System</h1>
                        <p class="mb-4">Experience seamless data management and operational excellence with advanced integration</p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 col-sm-8">
                        <p>In 2017, responding to the company’s growth and the increasing complexity of the business process, Berkat Safety invested in an ERP system – SAP. It seamlessly facilitates effective data processing and information from end to end and ensures that all products are processed and delivered to our customers on time.</p>
                        <p>Embracing the rapid development of the digital world, Berkat Safety also exists in digital platforms allowing our product users to easily access our extensive product ranges and increase engagement with them.</p>
                    </div>
                    <div class="col-12 col-sm-4">
                        <img src="'.url('/').'/images/image-berkat-3.webp" alt="">
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">Integrated System</h1>
                            <p class="mb-4">Experience seamless data management and operational excellence with advanced integration</p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-sm-8">
                            <p>In 2017, responding to the company’s growth and the increasing complexity of the business process, Berkat Safety invested in an ERP system – SAP. It seamlessly facilitates effective data processing and information from end to end and ensures that all products are processed and delivered to our customers on time.</p>
                            <p>Embracing the rapid development of the digital world, Berkat Safety also exists in digital platforms allowing our product users to easily access our extensive product ranges and increase engagement with them.</p>
                        </div>
                        <div class="col-12 col-sm-4">
                            <img src="'.url('/').'/images/image-berkat-3.webp" alt="">
                        </div>
                    </div>
                '],
            (object)['slug' => 'award', 'name' => 'Award', 'content_id' => '
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-primary mb-0">Award-Winning Commitment</h1>
                        <p class="mb-4">Awarded for our dedication to delivering world-class safety solutions.</p>
                        <p>Our journey of excellence has been marked by numerous accolades that recognize our unwavering commitment to safety, innovation, and service quality. These awards reflect the trust and confidence our clients and partners place in us, inspiring us to continue pushing boundaries and setting new standards in the industry.</p>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-12 col-sm-5">
                        <img src="'.url('/').'/images/award-1.webp" alt="ISO 9001:2005 - SAI Global">
                    </div>
                    <div class="col-12 col-sm-5 ms-sm-auto">
                        <img src="'.url('/').'/images/award-2.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-12 col-sm-5">
                        <img src="'.url('/').'/images/award-2.webp" alt="ISO 9001:2005 - SAI Global">
                    </div>
                    <div class="col-12 col-sm-5 ms-sm-auto">
                        <img src="'.url('/').'/images/award-2.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-12 col-sm-5">
                        <img src="'.url('/').'/images/award-4.webp" alt="ISO 9001:2005 - SAI Global">
                    </div>
                    <div class="col-12 col-sm-5 ms-sm-auto">
                        <img src="'.url('/').'/images/award-5.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-12 col-sm-5">
                        <img src="'.url('/').'/images/award-7.webp" alt="ISO 9001:2005 - SAI Global">
                    </div>
                    <div class="col-12 col-sm-5 ms-sm-auto">
                        <img src="'.url('/').'/images/award-8.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-12 col-sm-5">
                        <img src="'.url('/').'/images/award-9.webp" alt="ISO 9001:2005 - SAI Global">
                    </div>
                    <div class="col-12 col-sm-5 ms-sm-auto">
                        <img src="'.url('/').'/images/award-10.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                    </div>
                </div>
                ', 'content_en' => '
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-primary mb-0">Award-Winning Commitment</h1>
                            <p class="mb-4">Awarded for our dedication to delivering world-class safety solutions.</p>
                            <p>Our journey of excellence has been marked by numerous accolades that recognize our unwavering commitment to safety, innovation, and service quality. These awards reflect the trust and confidence our clients and partners place in us, inspiring us to continue pushing boundaries and setting new standards in the industry.</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-5">
                        <div class="col-12 col-sm-5">
                            <img src="'.url('/').'/images/award-1.webp" alt="ISO 9001:2005 - SAI Global">
                        </div>
                        <div class="col-12 col-sm-5 ms-sm-auto">
                            <img src="'.url('/').'/images/award-2.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                        </div>
                    </div>
                    <div class="row align-items-center mb-5">
                        <div class="col-12 col-sm-5">
                            <img src="'.url('/').'/images/award-2.webp" alt="ISO 9001:2005 - SAI Global">
                        </div>
                        <div class="col-12 col-sm-5 ms-sm-auto">
                            <img src="'.url('/').'/images/award-2.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                        </div>
                    </div>
                    <div class="row align-items-center mb-5">
                        <div class="col-12 col-sm-5">
                            <img src="'.url('/').'/images/award-4.webp" alt="ISO 9001:2005 - SAI Global">
                        </div>
                        <div class="col-12 col-sm-5 ms-sm-auto">
                            <img src="'.url('/').'/images/award-5.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                        </div>
                    </div>
                    <div class="row align-items-center mb-5">
                        <div class="col-12 col-sm-5">
                            <img src="'.url('/').'/images/award-7.webp" alt="ISO 9001:2005 - SAI Global">
                        </div>
                        <div class="col-12 col-sm-5 ms-sm-auto">
                            <img src="'.url('/').'/images/award-8.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                        </div>
                    </div>
                    <div class="row align-items-center mb-5">
                        <div class="col-12 col-sm-5">
                            <img src="'.url('/').'/images/award-9.webp" alt="ISO 9001:2005 - SAI Global">
                        </div>
                        <div class="col-12 col-sm-5 ms-sm-auto">
                            <img src="'.url('/').'/images/award-10.webp" alt="Participant Award - PT Nikamas Gemilang Divisi Nike">
                        </div>
                    </div>
                ']
        ];
        
    }
}
