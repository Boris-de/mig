#!/usr/bin/perl -w
#
# Contributed & written by : Dan Lowe <dan@tangledhelix.com>
# This used to be in the main Mig distribution but is now deprecated and is
# in the contrib package instead.
#
# Converts meta-files from old Mig (0.83 and before) to new Mig (0.90 and
# later).  This does the current directory, but you can grab
# "convert-metafiles.sh" too which does it recursively for all subfolders.
#

use strict;
use Cwd;
use Getopt::Std;

my $basedir = cwd();

my %opt = ();
getopts("I:", \%opt);

die "No filename specified.\n" unless $opt{"I"};

my $inputfile = $opt{"I"};

my %hidden = ();
my %comments = ();
my %ecomments = ();
my ($i, $filename);

open(IN, $inputfile);
while (<IN>) {

  chomp;
  chdir "$basedir/$_";

  if ( -f ".hidden.txt" ) {
    %hidden = ();		# empty between directories
    open(HIDDEN, ".hidden.txt") or die "Can't open .hidden.txt\n";
    while (<HIDDEN>) {
      chomp;
      $hidden{$_} = 1;
    }
    close HIDDEN;
    open(NEWCF, ">>mig.cf") or die "Can't open mig.cf for writing\n";
    print NEWCF "\n<Hidden>\n";
    foreach $i (sort keys %hidden) {
      print NEWCF "$i\n";
    }
    print NEWCF "</Hidden>\n\n";
    close NEWCF;
    unlink ".hidden.txt";
  }

  if ( -f ".comments.txt" ) {
    %comments = ();		# empty between directories
    open(COMMENTS, ".comments.txt") or die "Can't open .comments.txt\n";
    while (<COMMENTS>) {
      chomp;
      if (/^ITEM/) {
        $filename =  $_;
        $filename =~ s/^ITEM //;
      } else {
        $comments{$filename} .= " $_";
      }
    }
    close COMMENTS;
    open(NEWCF, ">>mig.cf") or die "Can't open mig.cf for writing\n";
    foreach $i (sort keys %comments) {
      print NEWCF "\n<Comment \"$i\">\n";
      print NEWCF $comments{$i}, "\n";
      print NEWCF "</Comment>\n";
    }
    close NEWCF;
    unlink ".comments.txt";
  }

  if ( -f ".exif_comments.txt" ) {
    rename ".exif_comments.txt", "exif.inf";
  }
}
