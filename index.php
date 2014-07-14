<?php require 'inc/layout/header.php' ?>
		
<div id="main">

	<section class="main-section" id="intro" name="intro">
		<h2>Greetings &amp; salutations!</h2>
		<p>This is a quick intro paragraph about me that will be filled in later. This is in no way meant to be final copy for this section of the site. If you see this on the live version of the site, don't hire me because I'm clearly a fucking idiot.</p>
		<p>Thanks,</p>
		<h2 style="font-family: Impact; font-size: 36px;">Insert signature graphic here</h2>
	</section>

	<section class="main-section" id="clients" name="clients">
		<h2 style="font-family: Impact; font-size: 36px;">INSERT MAGICAL SCROLLY-GIG DOODAD THING HERE</h2>
	</section>
	
	<section class="main-section" id="development" name="development">
		<h2>Development</h2>
		
		<article class="links-subsection" id="links" name="links">
			<h3>Links</h3>
			<ul>
				<li><a target="_blank" href="http://curvolabs.com">Curvo Labs</a> &ndash; Miscellaneous features, fixes, and styling added to medical equipment marketplace app that was built on CodeIgniter and also incorporated Composer, Phinx, Guzzle, and Stripe (among others).</li>
				<li><a target="_blank" href="http://omegaconvention.com">OmegaCon</a> &ndash; Multiple (annual) responsive themes, content development, SEO, promotion, ecommerce, custom reports for registration desk and various department heads.</li>
				<li><a target="_blank" href="http://khameleon.org/work/hslc">Health Sciences Library Consortium</a> &ndash; CSS build, most of a Drupal theme (the latter was handed off to the Commonwealth of Pennsylvania's in-house developers due to a last-minute change of plans on their end. As far as I know, the project is still in bureaucratic purgatory, hence the layout's being hosted on my own web space.)</li>
				<li><a target="_blank" href="http://coreknowledge.org">Core Knowledge Foundation</a> &ndash; CSS build, many custom PHP components.</li>
				<li><a target="_blank" href="http://blog.coreknowledge.org">Core Knowledge Blog</a> &ndash; Custom WordPress theme based on CSS build from the main site above.</li>
				<li><a target="_blank" href="http://books.coreknowledge.org">Core Knowledge Bookstore</a> &ndash; X-Cart theming, management, warehouse integration with UPS WorldShip and Stamps.com, accounting/inventory integration with Great Plains, custom database triggers to handle tracking number postback and buyer notifications.</li>
				<li><a target="_blank" href="http://rhainsure.com">Resort Hotel Association</a> &ndash; CSS build, many custom PHP components.</li>
				<li><a target="_blank" href="http://khameleon.org/work/applianceo">Appliance Outlet</a> &ndash; CSS build, light graphical work.</li>
				<li><a target="_blank" href="http://8great.org">James V. Brown Summer Reading Program</a> &ndash; CSS build converted from a print ad, so lots of optimization was needed to achieve reasonable load times.</li>
				<li><a target="_blank" href="http://tigerfuel.com">Tiger Fuel</a> &ndash; Numerous content updates, reworking layout on certain pages, troubleshooting various features (like the Flash/XML accordion on the homepage), and adding features to the supply chain management system.</li>
				<li><a target="_blank" href="http://cvilleshop.com">Charlottesville Shopping</a> &ndash; Troubleshooting of maps, Facebook integration, and coupon generator.</li>
			</ul>
		</article>
		
		<article class="links-subsection" id="code-samples" name="code-samples">
			<h3>Code Samples</h3>
			<ul>
				<li><a target="_blank" href="https://github.com/toastyghost/auth-net-ajax">Auth.net AJAX component</a> &ndash; This is a PHP component that is called via AJAX to submit a payment request to Authorize.net and report back its success status. It also includes logic related to the event registration wizard for which it was originally created, rendering the user's "finish later" token useless (but still remembering that it has been assigned) if the transaction is processed successfully.</li>
				<li><a target="_blank" href="https://github.com/toastyghost/python-cloud-upload">Rackspace Cloud Sideloader</a> &ndash; This is a Python command-line utility for uploading from the local dedicated server filesystem to Rackspace Cloud Files. Its purpose was to allow legacy PHP4 applications, which are not officially supported by Rackspace, to make programmatic calls via the cloud API.</li>
				<li><a target="_blank" href="https://github.com/toastyghost/auto-body-page">SilverStripe Page Type</a> &ndash; This is a component that contains both view and controller classes for a new page type in SilverStripe, an ORM-based open source CMS that uses the MVC design pattern.</li>
				<li><a target="_blank" href="https://github.com/toastyghost/wordpress_password">WordPress Password Module for Drupal 7.x</a> &ndash; This is a Drupal 7 module that implements PHPass (the third-party crypto library used by WordPress) to allow seamless login after a WordPress-to-Drupal migration, without the site ever being aware of the stored password. It works in conjunction with a command-line migration script that saves the PHPass hash for the unknown password to Drupal's user.data blob.</li>
			</ul>
		</article>
	</section>
	
	<section class="main-section" id="design" name="design">
		<h2>Design</h2>
		<blockquote>Coming soon&hellip;</blockquote>
	</section>

	<section class="main-section" id="resume" name="resume">
		<h2>R&eacute;sum&eacute;</h2>
		
		<section class="resume-subsection" id="experience-section" name="experience">
			<h3>Experience</h3>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">CodeIgniter Developer</h4> &ndash;
					<span class="employer-name">Curvo Labs</span> &ndash;
					<span class="period">
						<time class="employment-start" datetime="2014-01" certainty="circa">January 2014</time> to 
						<time class="employment-end" datetime="<?= date('Y-m') ?>" certainty="circa">present</time>
					</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Added features to medical equipment marketplace app targeting hospital purchasers and vendors.</li>
						<li>Performed QA &amp; code review using Atlassian's suite of agile development products.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">Web Administrator &amp; Co-Founder</h4> &ndash;
					<span class="employer-name">OmegaCon</span> &ndash;
					<span class="period">
						<time class="employment-start" datetime="2013-05" certainty="circa">May 2013</time> to 
						<time class="employment-end" datetime="<?= date('Y-m') ?>" certainty="circa">present</time>
					</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Built annual responsive WordPress themes based on Bones and Joints.</li>
						<li>Created online store using WPEC and UAM for user class-specific purchasable items.</li>
						<li>Directed art &amp; content asset creation; created some minor graphical assets.</li>
						<li>Performed SEO &amp; directory listings; site brought in thousands of visitors and $7500 in presales over a few months for a first-year event with a $0 advertising budget. (Top Google SERP placement achieved for targeted terms.)</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">RETS Developer</h4> &ndash; 
					<span class="employer-name">Gayle Harvey Real Estate</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2013-04" certainty="circa">Apr 2013</time> to 
						<time class="employment-end" datetime="2013-11" certainty="circa">Nov 2013</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Created automated MLS property listing aggregator using the phRETS library.</li>
						<li>Created proof-of-concept cloud service for property photos using phRETS, Unirest, and the imgur API.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">Drupal Developer</h4> &ndash; 
					<span class="employer-name">Se&#241;or Wooly</span> &ndash;
					<span class="period"> 
						<time class="employment-start" datetime="2012-03" certainty="circa">Mar 2012</time> to 
						<time class="employment-end" datetime="2013-07" certainty="circa">Jul 2013</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Created user and content migration scripts for digital teaching materials site going from WordPress to Drupal.</li>
						<li>Built custom Drupal modules for cryptographic and user account hierarchy functionalities.</li>
						<li>Designed teacher management interface that allowed the creation of classes, student accounts, assignments, and reports.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">WordPress Developer</h4> &ndash; 
					<span class="employer-name">DSML Executive Search</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2013-04" certainty="circa">Apr 2013</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Managed tight timeline subcontracting on behind-schedule project; got it to an on-schedule release with under a week's notice.</li>
						<li>Created custom data structures using WordPress Types for management of executive searches and a staff directory.</li>
						<li>Wrote template code for retrieval of custom data types and their display in an existing theme.</li>
						<li>Implemented attractive, dynamic widgets using existing JavaScript components.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">SilverStripe Developer</h4> &ndash; 
					<span class="employer-name">Okay Yellow</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2011-12" certainty="circa">Dec 2011</time> to 
						<time class="employment-end" datetime="2012-01" certainty="circa">Jan 2012</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Developed modular extensions for SilverStripe, a PHP 5.2+ -based OOP open-source CMS.</li>
						<li>Added features to car dealership chain's inventory management system.</li>
						<li>Created and modified block templates for printable coupons and various online promotions.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">Web Developer</h4> &ndash; 
					<span class="employer-name">The Ivy Group, Ltd.</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2008-12" certainty="circa">Dec 2008</time> to 
						<time class="employment-end" datetime="2011-09" certainty="circa">Sep 2011</time>
					</span>
					<span class="arrangement-type"></span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Built websites for clients of full-service advertising/PR agency.</li>
						<li>Created rich, Flash-like experiences using lightweight JavaScript event bindings.</li>
						<li>Co-created proprietary information management system (Mimik&trade;) used to rapidly build databases and page templates.</li>
						<li>Conducted requirements-gathering meetings, focus-group testing, client training seminars, and employment interviews.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">Helpdesk Technician</h4> &ndash; 
					<span class="employer-name">Sheridan Real Estate</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2007-06" certainty="circa">Jun 2006</time> to 
						<time class="employment-end" datetime="2008-10" certainty="circa">Oct 2008</time>
					</span>
					<span class="arrangement-type">(part-time)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Built and maintained computers for use by agents.</li>
						<li>Performed troubleshooting on hardware and software issues.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience">
				<header class="job-headline">
					<h4 class="job-title inline">Web Application Developer</h4> &ndash; 
					<span class="employer-name">Northern Arizona Muscle Tissue Institute</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2006-10" certainty="circa">Oct 2006</time> to 
						<time class="employment-end" datetime="2006-12" certainty="circa">Dec 2006</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Added features to massage therapy appointment scheduling system.</li>
						<li>Migrated hard-coded aspects of application to data-driven versions.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience old">
				<header class="job-headline">
					<h4 class="job-title inline">Graphic Designer</h4> &ndash; 
					<span class="employer-name">Commonwealth Engineering &amp; Construction</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2006-08" certainty="circa">Aug 2006</time> to 
						<time class="employment-end" datetime="2006-10" certainty="circa">Oct 2006</time>
					</span>
					<span class="arrangement-type">(contract)</span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Created photorealistic rendering of non-existent house for cover/promotional art for building proposals.</li>
						<li>Designed new company logo based on scans of previous one from hand-drawn blueprints.</li>
						<li>Designed and built company website under its previous name, Mapstone Engineering.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience old">
				<header class="job-headline">
					<h4 class="job-title inline">Quality Assurance Lead</h4> &ndash; 
					<span class="employer-name">MyNetCentral</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="2001-07" certainty="circa">Jul 2001</time> to 
						<time class="employment-end" datetime="2002-03" certainty="circa">Mar 2002</time>
					</span>
					<span class="arrangement-type"></span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Oversaw testing and redevelopment of customer-reported issues with web hosting service.</li>
						<li>Performed administration of accounts for customers with special requirements.</li>
						<li>Assisted in homepage redesign to attract a larger userbase.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience old">
				<header class="job-headline">
					<h4 class="job-title inline">Software Test Engineer</h4> &ndash; 
					<span class="employer-name">Prime Meridian Software</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="1999-11" certainty="circa">Nov 1999</time> to 
						<time class="employment-end" datetime="2001-08" certainty="circa">Aug 2001</time>
					</span>
					<span class="arrangement-type"></span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Co-managed new test team for medical systems firm that had no formal testing process prior to my starting.</li>
						<li>Designed/developed automated tests in Rational Robot, Segue SilkTest, and Microsoft Visual Basic.</li>
						<li>Created test team documentation in accordance to Rational Unified Process.</li>
					</ul>
				</section>
			</article>
			
			<article class="work-experience old">
				<header class="job-headline">
					<h4 class="job-title inline">Graphics Technician</h4> &ndash; 
					<span class="employer-name">McQuay International</span> &ndash; 
					<span class="period">
						<time class="employment-start" datetime="1998-08" certainty="circa">Aug 1998</time> to 
						<time class="employment-end" datetime="1999-03" certainty="circa">Mar 1999</time>
					</span>
					<span class="arrangement-type"></span>
				</header>
				
				<section class="job-description">
					<ul>
						<li>Modified scanned images of HVAC designs to meet strict formatting criteria.</li>
						<li>Images were used in system that let builders access designs from terminal in machine shop.</li>
					</ul>
				</section>
			</article>
		</section>
		
		<section class="resume-subsection" id="education-section" name="education">
			<h3>Education</h3>
			
			<article class="school-line">
				<h4 class="school-name inline">Massachusetts Institute of Technology OpenCourseWare</h4> &ndash; 
				<span class="program-name">Computer Science</span> &ndash; 
				<span class="period">
					<time class="education-start" datetime="2012" certainty="circa">2012</time> to 
					<time class="education-end" datetime="2013" certainty="circa">2013</time>
				</span>
				<span class="arrangement-type">(self-directed audit)</span>
			</article>
			
			<article class="school-line">
				<h4 class="school-name inline">University of Virginia</h4> &ndash; 
				<span class="program-name">Engineering &amp; Applied Science</span> &ndash; 
				<span class="period">
					<time class="education-start" datetime="2000" certainty="circa">2000</time>
				</span>
				<span class="arrangement-type">(withdrew in good standing)</span>
			</article>
			
			<article class="school-line">
				<h4 class="school-name inline">Blue Ridge Community College</h4> &ndash; 
				<span class="program-name">English Composition</span> &ndash; 
				<span class="period">
					<time class="education-start" datetime="1999" certainty="circa">1999</time> to
					<time class="education-end" datetime="2000" certainty="circa">2000</time>
				</span>
				<span class="arrangement-type">(coursework)</span>
			</article>
			
			<article class="school-line">
				<h4 class="school-name inline">James Madison University</h4> &ndash; 
				<span class="program-name">Integrated Science and Technology</span> &ndash; 
				<span class="period">
					<time class="education-start" datetime="1998" certainty="circa">1998</time> to
					<time class="education-end" datetime="1999" certainty="circa">1999</time>
				</span>
				<span class="arrangement-type">(coursework)</span>
			</article>
		</section>
	</section>
	
	<section class="main-section" id="contact" name="contact">
		<h2>Contact</h2>
		
		<span id="contact-container">
			<blockquote>If you have a great idea for a project and are interested in working together to make your vision a reality, drop me a line below!</blockquote>
			
			<form name="contact" id="contact-form" method="post" action="contact.php">
				<label for="name">Name</label> <input class="textbox" type="text" name="name"><br>
				<label for="email">Email</label> <input class="textbox" type="email" name="email"><br>
				
				<label for="company">Company</label> <input class="textbox"type="text" name="company"><br>
				<label for="phone">Phone</label> <input class="textbox" type="tel" name="phone"><br><br>
				
				<span class="sep-line">
					<label class="placeholder">&nbsp;</label>
					<label class="visible" for="project">Description of Project</label><br>
					
					<label class="placeholder">&nbsp;</label>
					<textarea name="project"></textarea><br><br>
					
					<label class="placeholder">&nbsp;</label>
					<label class="visible" for="budget">Estimated Budget</label><br>
					
					<label class="placeholder">&nbsp;</label>
					<select name="budget">
						<option value="Under $1k">Under $1,000</option>
						<option value="$1k-2.5k">$1,000-2,500</option>
						<option value="$2.5-5k">$2,500-5,000</option>
						<option value="$5k-10k">$5,000-10,000</option>
						<option value="$10k-25k">$10,000-25,000</option>
						<option value="Over $25k">Over $25,000</option>
					</select><br><br>
					
					<label class="placeholder">&nbsp;</label>
					<input id="submit" type="submit">
				</span>
			</form>
		</span>
		
	</section>
</div>
		
<?php require 'inc/layout/footer.php' ?>