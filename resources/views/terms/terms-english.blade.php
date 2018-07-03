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
	</style>
@endsection

@section('content')
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Kvittens</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice 4.1.3  (Unix)">
	<META NAME="AUTHOR" CONTENT="Alireza Heidarian">
	<META NAME="CREATED" CONTENT="20180601;12020000">
	<META NAME="CHANGEDBY" CONTENT="kent ">
	<META NAME="CHANGED" CONTENT="20180620;12470300">
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
			 <li><a data-ajax="false" class="ui-btn-active">{{ __('messages.Terms') }}</a></li>
			  <li class="done-btn" id="dataSave"></li> </ul>
			</div><!-- /navbar -->
		</div>
	</div>


<BODY LANG="sv-SE" TEXT="#000000" LINK="#0563c1" DIR="LTR">
<DIV TYPE=HEADER>
	<BR>
</DIV>

<P CLASS="western"><A NAME="_GoBack"></A><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">The
restaurant app &quot;Anar&quot; is delivered by Dastjar AB (Dastjar),
which is a development company with its own service platform. Dastjar
delivers digital services to small and medium-sized companies.
Dastjar's vision is to help small and medium-sized businesses to
grow, while serving you with a service (app) that simplifies and
saves you time when ordering in your favorite restaurant. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">Dastjar’s
service is location based. It saves your time and serves you best
wherever you are. In order to do that, we need your permission to
store your login information. It helps us to ensure that your order
is delivered to you and no one else. </SPAN></FONT></FONT></FONT>
</P>
<UL>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	mobile app &quot;anar&quot; is free to use, as an additional service
	to restaurants.</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	service may not be used for any purpose other than its intended
	purpose.</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">The
	&quot;Mobile app&quot; is intended for use by restaurant visitors
	and may not be used or configured for other uses, without the
	consent of Dastjar</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">Dastjar
	takes no responsibility for service interruptions or other problems
	due to errors beyond its control. Although, Dastjar will receive
	information about it and wants to do its best to help with the
	solution.</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2 STYLE="font-size: 9pt"><SPAN LANG="en">Dastjar
	does not bear responsibility for errors caused by configuration
	changes.</SPAN></FONT></FONT></FONT></P>
</UL>
<P LANG="en-US" STYLE="margin-left: 1.27cm; margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">At
Dastjar we respect and protect your personal integrity. Here we
describe when and why we collect, use and share personal information
about people who use our websites, mobile applications, or use our
digital channels to contact us (&quot;online services&quot;).</SPAN></FONT></FONT></FONT></P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US"><BR></SPAN></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">By
using our online services or by contacting us, you agree to this
privacy policy and that we process your personal information. You
also agree that we can use digital communication channels to contact
you. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT COLOR="#212121"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US">For
questions concerning personal data and data protection, contact us at
</SPAN></FONT></FONT></FONT><A HREF="mailto:info@dastjar.com"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US">info@dastjar.com</SPAN></FONT></FONT></A><FONT COLOR="#212121"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US">.
</SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">How
we collect information, what we collect, and why we collect it.</SPAN></FONT></FONT></FONT></P>
<H1 CLASS="western" STYLE="font-style: normal; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en">Personal
data</SPAN></FONT></FONT></FONT></H1>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en">These
are basic information that you provide when creating a user account
with us, such as name, email address or phone number and a reference
back to the authentication service that provided us with the
information if applicable. We store communications and correspondence
after your delivery has been completed.</SPAN></FONT></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en">These
data are your personal information, which means that they can be
deleted at your request.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en">
The information is required to fulfill our commitment to you, in
order to fulfill our legal obligations and for our legitimate
interest.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en"><I>Eligible
interests</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en">:
crime prevention or suspected illegal activity (fraud).</SPAN></FONT></FONT></FONT></P>
<P LANG="en-US" CLASS="western" STYLE="text-decoration: none"><BR><BR>
</P>
<H1 CLASS="western" STYLE="font-style: normal; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">Location
Data</SPAN></FONT></FONT></FONT></H1>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">If
you allow sharing your location on your mobile device, your position
will be used to customize our online services and user experience as
well as to prevent fraud. We can also use your location to show local
offers to you.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Legal
basis for processing</I></SPAN></FONT><FONT COLOR="#212121"><SPAN LANG="en">:
necessary to fulfill our commitment to you and to satisfy our
legitimate interests.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Eligible
interests:</I></SPAN></FONT><FONT COLOR="#212121"><SPAN LANG="en"> to
prevent crime or suspicious illegal activities (fraud) and to provide
you with content and updates during the ordering process and to
facilitate the efficient operation and follow-up of our business.</SPAN></FONT></FONT></FONT></P>
<H1 CLASS="western"></H1>
<H1 CLASS="western"><FONT COLOR="#222222"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US"><I><SPAN STYLE="text-decoration: none">Transaction
data</SPAN></I></SPAN></FONT></FONT></FONT><FONT COLOR="#212121"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US"><SPAN STYLE="text-decoration: none">
</SPAN></SPAN></FONT></FONT></FONT>
</H1>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">Details
of the transactions you perform through our services. This is used to
track your orders, give you an order history, content customization,
to enhance your user experience. These data are anonymous and not
portable. </SPAN></FONT></FONT></FONT>
</P>
<P CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">
In order to fulfill our legal obligations, perform our commitment to
you and meet our legitimate interests.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><SPAN LANG="en-US"><BR></SPAN><FONT COLOR="#212121"><SPAN LANG="en"><I>Information
about user behavior</I></SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">Details
about your visits to our online services including, but not limited
to, traffic data, and other communications data. This is used to
improve our online services and to detect problems. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">These
data are anonymous and not portable. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><SPAN STYLE="font-style: normal"><SPAN STYLE="text-decoration: none">Technical
information</SPAN></SPAN></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><U>
</U></SPAN></FONT></FONT></FONT>
</H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">
necessary to fulfill our commitment to you and for our legitimate
interest. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><I>Eligible
interests:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">
Providing and improving user experience and preventing crimes or
suspected illegal activities (fraud). </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">Technical
data </SPAN></FONT></FONT>
</H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">When
using our online services, we can collect technical information such
as the type of mobile device you are using, a unique device
identifier such as IMEI number, device token, MAC- address for device
wireless network interface, IP address, mobile number used by the
device, mobile network information, versions of operating system and
browser. This is used to improve our online services and to detect
problems. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">These
data are anonymous and not portable. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><I>Legal
basis for processing:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">
necessary to fulfill our commitment to you and to satisfy our
legitimate interests. </SPAN></FONT></FONT></FONT></FONT>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=3><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US"><I>Eligible
interests:</I></SPAN></FONT></FONT><FONT COLOR="#212121"><FONT SIZE=2><SPAN LANG="en-US">
crime prevention or suspected illegal activity (fraud).</SPAN></FONT></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Payment
Data</I></SPAN></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">Payment
options linked to orders and transactions are handled by our payment
provider and card data is never stored in any of our online services.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT><FONT COLOR="#212121"><SPAN LANG="en">
To fulfill our commitment to you and to fulfill our legal
obligations.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<H1 CLASS="western"><FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">Use
of cookies</SPAN></FONT></FONT></FONT></H1>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">We
use cookies to identify, track and count visitors to online services,
for more information visit our Cookie Policy.</SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">These
data are anonymous and not portable.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Legal
basis for processing:</I></SPAN></FONT><FONT COLOR="#212121"><SPAN LANG="en">
To fulfill our commitment to you and to fulfill our legal
obligations.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>Eligible
interests:</I></SPAN></FONT><FONT COLOR="#212121"><SPAN LANG="en">
Cookies are used to provide you with content and updates during the
ordering process and facilitate the efficient operation and operation
of our business.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><I><FONT COLOR="#212121"><SPAN LANG="en">Sensitive
personal information</SPAN></FONT></I></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">We
do not collect consciously or deliberately data classified as
&quot;sensitive personal data&quot;.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I>How
we protect personal data?</I></SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">Your
personal data is protected by both technical and organizational
measures. All personal data is stored securely, access to data is
monitored and restricted to those whose duties require it.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I><B>How
long will we keep your data?</B></I></SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">We
retain only personal information for as long as necessary to fulfill
our commitments to you and to comply with our legal obligations (eg
accounting purposes).</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I><B>Your
rights</B></I></SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">You
are entitled to;</SPAN></FONT></FONT></FONT></P>
<UL>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">access
	to your data</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">delete
	your data / account</SPAN></FONT></FONT></FONT></P>
	<LI><P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">complaining
	to a supervisory authority</SPAN></FONT></FONT></FONT></P>
	<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
	</P>
</UL>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I><B>Changes
to the policy</B></I></SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">This
policy can be changed at any time. Please check the latest update
date before adding a new order. If you do not approve the changes,
stop using our online services.</SPAN></FONT></FONT></FONT></P>
<P LANG="en" STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<BR>
</P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en"><I><B>Cookies
Policy</B></I></SPAN></FONT></FONT></FONT></P>
<P STYLE="margin-bottom: 0cm; background: #ffffff; border: none; padding: 0cm; line-height: 100%; text-decoration: none">
<FONT FACE="Helvetica, sans-serif"><FONT SIZE=2><FONT COLOR="#212121"><SPAN LANG="en">We
use cookies to identify users in our online services, which is
required for automatic login for registered users. If you change the
settings of your browser to not allow cookies, this will affect the
functionality of our services and we can not guarantee that our
services will work as promised.</SPAN></FONT></FONT></FONT></P>
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