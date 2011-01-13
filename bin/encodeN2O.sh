#!/bin/sh
#==========================================================================
# COVERALL CREW - INTERNAL USE ONLY
#==========================================================================
#
# DO NOT EDIT THIS SCRIPT FROM OUTSIDE THE CVS DEVELOPMENT TREE. IF YOU DO
# YOUR CHANGES CAN AND WILL BE LOST.
#
#==========================================================================
#
# Uncomment this to export N2O using a particular tag. (Use "HEAD" to get
# the latest.)
#tag="VERSION_1_6"
tag="STABLE"
#tag="HEAD"
#debug=1

# set the location of things...
BASEDIR="/Users/$USER/Sites/build"
TEMPDIR="$BASEDIR/.tmp"
TARGETDIR="/Users/$USER/Sites"

if [ `whoami` = "root" ]; then
        echo "You cannot be root to run this script."
        exit
fi

if [ ! -z $2 ]; then
	if [ $2 = "--skip-merge" ]; then
		skipmerge=yes
	fi	
fi

if [ -z $tag ]; then
	tag="STABLE"
	filename="N2O.tgz"
else
	filename="N2O-$tag.tgz"
fi

addFileToRequireOnce()
{
	local _path=$1
	local _delete=$3

	if echo $_path | grep -q -E '.php$'; then
		cat $_path >> $2
		if [ -z $_delete ]; then
			rm $_path
		fi
	fi
}

addToRequireOnce()
{
	local _path=$1
	for file in `ls -1 $_path`; do 
		if [ -d $_path/$file ]; then
			addToRequireOnce $_path/$file $2
		elif echo $file | grep -q -E '.php$'; then
			addFileToRequireOnce $_path/$file $2
		fi
	done
}

ask_y()
{
        read -p "$1 [Y/n]: " RESPONSE

        case $RESPONSE in
        n*|N*)
                RESPONSE=1
        ;;
        *)
                RESPONSE=0
        ;;
        esac

        return $RESPONSE
}

ask_n()
{
        read -p "$1 [y/N]: " RESPONSE

        case $RESPONSE in
        y*|Y*)
                RESPONSE=1
        ;;
        *)
                RESPONSE=0
        ;;
        esac

        return $RESPONSE
}

cd $BASEDIR

if [ -e .tmp ]; then
        echo -n " - Cleaning up from previously failed deployment... "
        rm -rf .tmp
        echo "done"
fi


mkdir .tmp
cd .tmp

if ask_y "Do you want to use your local development copy of N2O?" ; then
	skip_tarball=1

	if [ -d $TARGETDIR/N2O-dev ]; then
		n2o_folder="$TARGETDIR/N2O-dev"
	else
		n2o_folder="$TARGETDIR/N2O"
	fi
	
	echo -n " + Grabbing files from $n2o_folder... "
	cp -r $n2o_folder CC_Framework
	echo "done"
else
	echo -n " + Exporting files from CVS... "
	echo -n "(using tag $tag) "
	/usr/bin/cvs -q export -r $tag CC_Framework >/dev/null
	echo "done"
fi

cd $TEMPDIR

if [ -z $skipmerge ]; then
	echo -n " + Merging files to reduce disk I/O... "
	requireOnce=CC_Framework/_RequireOnceFiles.php-new
	requireOnceAdmin=CC_Framework/_RequireOnceAdminFiles.php-new
	CC_Framework=`pwd`/CC_Framework
	
	addFileToRequireOnce $CC_Framework/CC_Utilities.php $requireOnce false
	addFileToRequireOnce $CC_Framework/CC_Database.php $requireOnce false
	addFileToRequireOnce $CC_Framework/CC_Component.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Application.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Action_Handler.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Button.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Buttons/CC_Text_Button.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Error.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_ErrorManager.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_FieldManager.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Record.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_RelationshipManager.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_User.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Window.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_Text_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_Multiple_Choice_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_SelectList_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_Date_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_DateTime_Field.php $requireOnce
	addFileToRequireOnce $CC_Framework/CC_Fields/CC_FloatNumber_Field.php $requireOnce

	addFileToRequireOnce $CC_Framework/CC_Summary.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Graph.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Image_Utilities.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Pie_Chart.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Summary_Content_Provider.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Summary_Filter.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_ZIP_File.php $requireOnceAdmin
	addFileToRequireOnce $CC_Framework/CC_Components/CC_Query_Addition.php $requireOnceAdmin
	addToRequireOnce $CC_Framework/CC_Components $requireOnceAdmin
	addToRequireOnce $CC_Framework/CC_Summaries $requireOnceAdmin
	addToRequireOnce $CC_Framework/CC_Summary_Content_Providers $requireOnceAdmin
	addToRequireOnce $CC_Framework/CC_Summary_Filters $requireOnceAdmin
	
	for i in $CC_Framework/CC_Handlers/CC_Summary*; do
		addFileToRequireOnce $i $requireOnceAdmin
	done
	
	addToRequireOnce $CC_Framework/CC_Buttons $requireOnce
	addToRequireOnce $CC_Framework/CC_Fields $requireOnce
	addToRequireOnce $CC_Framework/CC_Handlers $requireOnce

	rm -rf $CC_Framework/CC_Buttons
	rm -rf $CC_Framework/CC_Fields
	rm -rf $CC_Framework/CC_Handlers

	rm -rf $CC_Framework/CC_Components
	rm -rf $CC_Framework/CC_Summaries
	rm -rf $CC_Framework/CC_Summary_Content_Providers
	rm -rf $CC_Framework/CC_Summary_Filters

	rm -rf $CC_Framework/bin
	rm -f $CC_Framework/ChangeLog
	rm -f $CC_Framework/TODO.list
	rm -f $CC_Framework/RULES.txt
	rm -f $CC_Framework/.cvsignore

	mv `pwd`/CC_Framework/_RequireOnceFiles.php-new `pwd`/CC_Framework/_RequireOnceFiles.php
	mv `pwd`/CC_Framework/_RequireOnceAdminFiles.php-new `pwd`/CC_Framework/_RequireOnceAdminFiles.php

	if [ ! -z $tag ]; then
		touch `pwd`/CC_Framework/$tag
	fi

	if [ ! -z $debug ]; then
		cp `pwd`/CC_Framework/_RequireOnceFiles.php `pwd`/CC_Framework/._RequireOnceFiles.php-debug
		cp `pwd`/CC_Framework/_RequireOnceAdminFiles.php `pwd`/CC_Framework/._RequireOnceAdminFiles.php-debug
	fi
	echo "done"
fi

echo -n " + Encoding into binary format... "
php -q $BASEDIR/encoder.php -rcwf CC_Framework -o N2O
echo "done"

cd $BASEDIR 

if [ -d N2O ]; then
	rm -r N2O
fi
mv .tmp/N2O .
rm -r .tmp
find N2O -name '*.php' -exec touch {} \;

if [ -e $filename ]; then
	rm $filename
fi

if [ -z $skip_tarball ]; then
	echo -n " + Creating N2O.tgz tarball... "
	tar czf $filename N2O
	chmod o-r $filename
	echo "done"
fi

if [ ! -d N2O ]; then
	echo "!!! ALERT !!! For some reason the N2O folder does not exist. Redeploy!"
else
	echo "Encoding of N2O complete."

	if ask_y "Do you want to install it now?" ; then
		if [ -d $TARGETDIR/N2O-dev ]; then
			rm -r $TARGETDIR/N2O
			mv N2O $TARGETDIR
		else
			mv $TARGETDIR/N2O $TARGETDIR/N2O-dev
			mv N2O $TARGETDIR
			if [ -d $TARGETDIR/N2O-encoded ]; then
				rm -r $TARGETDIR/N2O-encoded
			fi
		fi
		
		echo -n " + Clearing eAccelerator's memory cache... "
		php $TARGETDIR/N2O/clear_eaccelerator.php http://localhost/N2O/eaccelerator.php
		echo "done"
	fi
fi

