package Playnet::Email;

use strict;
use MIME::Lite;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 	= qw(Exporter);
@EXPORT = qw(emailUser);

MIME::Lite->send('smtp', 'mail.playnet.com', 'Timeout' => 10);

sub emailUser(){
	
	my $to		= shift(@_);
	my $from	= shift(@_);
	my $subject	= shift(@_);
	my $content	= shift(@_);
	
	if($to !~ /^$/){
		
		my $msg = MIME::Lite->new('To' 		=> $to,
								  'From' 	=> $from,
								  'Bcc'		=> 'rafter@playnet.com',
  								  'Subject' => $subject,
								  'Type' 	=> 'text/html',
								  'Data' 	=> $content);
		
		eval{
			if(!$msg->send()){
				print "ERROR: Mail not sent.\n";
			}
		};
		if($@){
			print "ERROR: Unable to send email ($@).\n";
		}
		
	}
	
}

1;
