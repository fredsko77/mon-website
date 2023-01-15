#! /usr/bin/sh
echo 'Merge sur la preprod' 
git checkout preprod 
git status 
git merge dev  
git push  
git status
echo 'Merge de sur la master'
git checkout master 
git status 
git merge preprod 
git push 
git status 
echo 'Retour sur la branche dev'
git checkout dev 
git status
php bin/console cache:clear