@extends('layouts.master')
@section('head-scripts')
	<style type="text/css">
		.top-container{
			padding-left: 50px; 
    		padding-right: 50px; 
		}

	#delete-me-btn{
/*		background-color: #d25229;
*/	
		background: none;
    	color: #d25229;
	    border-radius: 10px;
	    border: none;
	}

	#delete_user_block{
		    display: inline-block;
		    position: absolute;
		    bottom: 10px;
		    left: 50%;
		    margin-left: -55px;
	}

	.ui-dialog{
		background-color: #fff !important;
	}

	.ui-controlgroup, #dialog-confirm + fieldset.ui-controlgroup {
    	width: 100%;
	}

	#dialog-confirm{
		display: none;
		/* color: #fff; */
	} 

	.ui-dialog .ui-dialog-buttonpane{
		text-align: center;
	}

	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
	}
	
	#dialog-confirm .ui-icon{
		float:left; 
		margin:12px 12px 20px 0;
		color: #fff;
	}

	#dialog-confirm .ui-icon-alert{
		color: #fff;
	}

	.ui-widget-overlay{
	    opacity: 0.5 !important;		
	}

	.dialog-no{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%) !important;
		color: #fff !important;
	}

	.dialog-no:hover{
		background: linear-gradient(to bottom, rgba(249,163,34,1) 0%, rgba(229,80,11,1) 100%);
		color: #fff;
	}

	.anar-logo {
	    max-width: 95px; 
/*    	margin-top: 5px;
*/    	margin-bottom: 2px;
	}
	</style>
@endsection

@section('content')
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Kvittens</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice 4.1.3  (Unix)">
	<META NAME="AUTHOR" CONTENT="Alireza Heidarian">
	<META NAME="CREATED" CONTENT="20180629;16220000">
	<META NAME="CHANGEDBY" CONTENT="Alireza Heidarian">
	<META NAME="CHANGED" CONTENT="20180629;16430000">
	<META NAME="AppVersion" CONTENT="16.0000">
	<META NAME="DocSecurity" CONTENT="0">
	<META NAME="HyperlinksChanged" CONTENT="false">
	<META NAME="LinksUpToDate" CONTENT="false">
	<META NAME="ScaleCrop" CONTENT="false">
	<META NAME="ShareDoc" CONTENT="false">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

	<STYLE TYPE="text/css">
	</STYLE>
</HEAD>

	<div data-role="header" class="header" data-position="fixed">
		<div class="nav_fixed">
			<div data-role="navbar"> 
				<ul> 
			<li><a href="{{url('user-setting')}}" data-ajax="false" class="text-left"><img src="{{asset('images/icons/backarrow.png')}}" width="11px"></a></li>
			 <li><a data-ajax="false" class="ui-btn-active">					
			 	<img src="{{asset('images/logo.png')}}" class="anar-logo">
				</a>
			</li>
			  <li class="done-btn" id="dataSave"></li> </ul>
			</div><!-- /navbar -->
		</div>
	</div>


<BODY LANG="sv-SE" TEXT="#000000" LINK="#0563c1" DIR="LTR">
<DIV TYPE=HEADER>
		<p style="text-align: center;     font-size: 25px;
    font-weight: bold;">
		{{ __('messages.Terms') }}
	</p>
	<BR>
</DIV>

<H1 CLASS="western"><SPAN LANG="en">Terms &amp; Conditions</SPAN></H1>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">The
&quot;Anar&quot; application (ANAR) transfers orders of food from the
customer (hereinafter &quot;the customer&quot;) to the
customer-selected restaurant. Each restaurant has specific opening
hours and outreach areas. When ordered, each individual restaurant is
responsible for processing. &quot;ANAR&quot; always tries to detect
and warn of any malfunction but is not responsible for delayed or
non-delivery due to any technical issues.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Ordering</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">To
shop for &quot;ANAR&quot; you must be 18 years old.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">All
personal data specified before and at the time of purchase must be
correct and belong to the person who orders the products. ANAR is not
responsible for non-delivery due to insufficient or incorrect
information in the order form.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Products</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">ANAR
aims to always keep all information on the app updated for any
changes in product range, but we reserve for certain products may be
finished for that day. Each individual restaurant is responsible for
updating its menu and notifying the customer of changes to the menu.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">ANAR
works for a high quality of the products offered through the app and
therefore has a great interest in taking part in the customer's
experience. </SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2>Hver
enkelt restaurant har den ultimate ansvaret for kvaliteten af ​​sine
produkter. </FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">If
the customer has complaints about individual products, the customer
will primarily address this with the individual restaurant.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">While
ANAR attest that all prices on the website are correct, we reserve
ourselves for any errors and the possibility that any restaurant has
changed its prices without notifying ANAR.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Payment</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">All
orders via ANAR are binding. Some restaurants offer their customers
to pay by credit card with ANAR. For further information, see the
respective restaurant's menu and checkout.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">All
prices on ANAR are inclusive of VAT unless otherwise stated. VAT on
food, catering and catering services is 12% otherwise the VAT is 25%.
Receipt and VAT specification are sent by e-mail from each restaurant
as these are the ones selling the products.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">ANY
service is free for the consumer ie you pay directly to the
restaurant via ANAR and it costs you as a final customer nothing.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Delivery</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">For
particularly long delivery times, the restaurant has the ultimate
responsibility to inform the customer about this.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en-US" CLASS="western"><BR><BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Return Policy</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none"><A NAME="_GoBack"></A>
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">The
sale of food via ANAR is not covered by the right of withdrawal under
the distance contract law (Act (2005: 59) on distance contracts and
agreements outside business premises). You can read more about right
of withdrawal and what products and services are exempt from right of
withdrawal on the Consumer Agency's website (</SPAN></FONT></FONT></FONT><A HREF="https://www.konsumentverket.se/"><FONT COLOR="#29b173"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN LANG="en-US"><U>Konsumentverkets
hemsida</U></SPAN></FONT></FONT></FONT></A><FONT COLOR="#29b173"><FONT FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><U>)</U></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Changes in Terms</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">ANAR
is entitled to change these terms without prior approval. ANAR
informs about the change at least one week before it enters into
force. The information is available on the website: dastjar.com. By
continuing to use ANAR after the change has come into effect, you
agree to the change.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H2 CLASS="western"><SPAN LANG="en"><B>Contact details</B></SPAN></H2>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">If
you would like to contact us at Customer Service, please contact us
via email info@dastjar.com.  </SPAN></FONT></FONT></FONT></FONT>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">We
answer most emails within 24 hours during weekdays.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">Opening
hours: Monday to Friday from 09:00 to 18:00</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">ANAR
is developed and operated by Dastjar AB with org. number 559123-5311</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en"><B>We
follow the recommendations of the General Complaints Board</B></SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en-US" CLASS="western"><BR><BR>
</P>
<H1 CLASS="western"><SPAN LANG="en-US">Integrity Policy</SPAN></H1>
<P CLASS="western"><SPAN LANG="en-US"><BR></SPAN><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">The
restaurant app &quot;Anar&quot; is delivered by Dastjar AB (Dastjar),
which is a development company with its own service platform. Dastjar
delivers digital services to small and medium-sized companies.
Dastjar's vision is to help small and medium-sized businesses to
grow, while serving you with a service (app) that simplifies and
saves you time when ordering in your favorite restaurant. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">Dastjar’s
service is location based. It saves your time and serves you best
wherever you are. In order to do that, we need your permission to
store your login information. It helps us to ensure that your order
is delivered to you and no one else. </SPAN></FONT></FONT></FONT>
</P>
<UL>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	mobile app &quot;anar&quot; is free to use, as an additional service
	to restaurants.</SPAN></FONT></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	service may not be used for any purpose other than its intended
	purpose.</SPAN></FONT></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	&quot;Mobile app&quot; is intended for use by restaurant visitors
	and may not be used or configured for other uses, without the
	consent of Dastjar</SPAN></FONT></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">Dastjar
	takes no responsibility for service interruptions or other problems
	due to errors beyond its control. Although, Dastjar will receive
	information about it and wants to do its best to help with the
	solution.</SPAN></FONT></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">Dastjar
	does not bear responsibility for errors caused by configuration
	changes.</SPAN></FONT></FONT></FONT></FONT></P>
</UL>
<P LANG="en-US" STYLE="margin-left: 1.27cm; margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">At
Dastjar we respect and protect your personal integrity. Here we
describe when and why we collect, use and share personal information
about people who use our websites, mobile applications, or use our
digital channels to contact us (&quot;online services&quot;).</SPAN></FONT></FONT></FONT></P>
<P CLASS="western"><FONT SIZE=2><SPAN LANG="en-US"><BR></SPAN></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">By
using our online services or by contacting us, you agree to this
privacy policy and that we process your personal information. You
also agree that we can use digital communication channels to contact
you. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">For
questions concerning personal data and data protection, contact us at
</SPAN></FONT></FONT></FONT><A HREF="mailto:info@dastjar.com"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">info@dastjar.com</SPAN></FONT></FONT></A><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">.
</SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">How
we collect information, what we collect, and why we collect it.</SPAN></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en"><I><U>Personal
data</U></I></SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">These
are basic information that you provide when creating a user account
with us, such as name, email address or phone number and a reference
back to the authentication service that provided us with the
information if applicable. We store communications and correspondence
after your delivery has been completed.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">These
data are your personal information, which means that they can be
deleted at your request.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">
The information is required to fulfill our commitment to you, in
order to fulfill our legal obligations and for our legitimate
interest.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Calibri, serif"><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en"><I>Eligible
interests</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><FONT SIZE=2><SPAN LANG="en">:
crime prevention or suspected illegal activity (fraud).</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en-US" CLASS="western" STYLE="text-decoration: none"><BR><BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><U>Location
Data</U></I></SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">If
you allow sharing your location on your mobile device, your position
will be used to customize our online services and user experience as
well as to prevent fraud. We can also use your location to show local
offers to you.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Legal
basis for processing</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">:
necessary to fulfill our commitment to you and to satisfy our
legitimate interests.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Eligible
interests:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">
to prevent crime or suspicious illegal activities (fraud) and to
provide you with content and updates during the ordering process and
to facilitate the efficient operation and follow-up of our business.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western"><FONT SIZE=2><SPAN LANG="en-US"><BR></SPAN></FONT><FONT COLOR="#222222"><FONT FACE="Times New Roman, serif"><FONT SIZE=2><SPAN LANG="en-US"><I><U>Transaction
data</U></I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><U>
</U></SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">Details
of the transactions you perform through our services. This is used to
track your orders, give you an order history, content customization,
to enhance your user experience. These data are anonymous and not
portable. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">
In order to fulfill our legal obligations, perform our commitment to
you and meet our legitimate interests.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><SPAN LANG="en-US"><BR></SPAN><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Information
about user behavior</I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">Details
about your visits to our online services including, but not limited
to, traffic data, and other communications data. This is used to
improve our online services and to detect problems. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">These
data are anonymous and not portable. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I><U>Technical
information</U></I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><U>
</U></SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">
necessary to fulfill our commitment to you and for our legitimate
interest. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I>Eligible
interests:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">
Providing and improving user experience and preventing crimes or
suspected illegal activities (fraud). </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I><U>Technical
data </U></I></SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">When
using our online services, we can collect technical information such
as the type of mobile device you are using, a unique device
identifier such as IMEI number, device token, MAC- address for device
wireless network interface, IP address, mobile number used by the
device, mobile network information, versions of operating system and
browser. This is used to improve our online services and to detect
problems. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">These
data are anonymous and not portable. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">
necessary to fulfill our commitment to you and to satisfy our
legitimate interests. </SPAN></FONT></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US"><I>Eligible
interests:</I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Arial, serif"><FONT SIZE=2><SPAN LANG="en-US">
crime prevention or suspected illegal activity (fraud).</SPAN></FONT></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><U>Payment
Data</U></I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">Payment
options linked to orders and transactions are handled by our payment
provider and card data is never stored in any of our online services.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">
To fulfill our commitment to you and to fulfill our legal
obligations.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><U>Use
of cookies</U></I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">We
use cookies to identify, track and count visitors to online services,
for more information visit our Cookie Policy.</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">
To fulfill our commitment to you and to fulfill our legal
obligations.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>Eligible
interests:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">
Cookies are used to provide you with content and updates during the
ordering process and facilitate the efficient operation and operation
of our business.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><U>Sensitive
personal information</U></I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">We
do not collect consciously or deliberately data classified as
&quot;sensitive personal data&quot;.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I>How
we protect personal data?</I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">Your
personal data is protected by both technical and organizational
measures. All personal data is stored securely, access to data is
monitored and restricted to those whose duties require it.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><B>How
long will we keep your data?</B></I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">We
retain only personal information for as long as necessary to fulfill
our commitments to you and to comply with our legal obligations (eg
accounting purposes).</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><B>Your
rights</B></I></SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">You
are entitled to;</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">-
access to your data</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">-
delete your data / account</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">-
complaining to a supervisory authority</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><B>Changes
to the policy</B></I></SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">This
policy can be changed at any time. Please check the latest update
date before adding a new order. If you do not approve the changes,
stop using our online services.</SPAN></FONT></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en"><I><B>Cookies
Policy</B></I></SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Courier New, serif"><FONT SIZE=2><FONT COLOR="#212121"><FONT FACE="inherit, serif"><SPAN LANG="en">We
use cookies to identify users in our online services, which is
required for automatic login for registered users. If you change the
settings of your browser to not allow cookies, this will affect the
functionality of our services and we can not guarantee that our
services will work as promised.</SPAN></FONT></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0.28cm; border: none; padding: 0cm; text-decoration: none">
<BR><BR>
</P>

<DIV TYPE=FOOTER>
 	@if(Auth::check())
 		<div id="delete_user_block"> 
					<form method="post" id="delete-me-form" action="{{ url('delete-me') }}" data-ajax="false">
						{{ csrf_field() }}
 							<button type="submit" id="delete-me-btn" class="btn btn-danger">Unregister</button>		
 					</form>
		</div> 


	<div id="dialog-confirm" title="Delete Account">
			<p>This action will remove all your personal data in the system.<br/><br/> Are you sure? Yes / No</p>
	</div>
 	@endif
	<div>
	<P ALIGN=RIGHT STYLE="margin-top: 1.15cm; margin-bottom: 0cm; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<SDFIELD TYPE=PAGE SUBTYPE=RANDOM FORMAT=PAGE>3</SDFIELD></P>
	<P STYLE="margin-bottom: 0cm; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<BR>
	</P>
	</div>
</DIV>

@endsection

@section('footer-script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript">
		$('#delete-me-form').submit(function(event){
			event.preventDefault();

			$( "#dialog-confirm" ).dialog({
					resizable: false,
					modal: true,
					buttons: [						
						{
							text: "No",
							"class": 'dialog-no',
							click: function() {
								$(this).dialog("close");
							}					
						},
						{
							text: "Yes",
							"class": 'dialog-yes',
							click: function() {
								$(this).dialog("close");
								$('#delete-me-form').unbind().submit();
							}
						}
		        ]
				
			}); 
	
		});
</script>

@endsection		


