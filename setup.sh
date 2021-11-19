wp plugin install woocommerce --path=/var/www/html --allow-root
wp plugin activate woocommerce --path=/var/www/html --allow-root
wp wc product create --name="Test Product" --type=simple --sku=WCCLITESTP --regular_price=20 --user=wordpress
