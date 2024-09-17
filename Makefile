zip_1_0: # zip archive for version 1.0
	rm -rf wp-update-breaker
	rm -rf wp-update-breaker.1.0.zip
	cp -r v1.0 wp-update-breaker
	zip -r wp-update-breaker.1.0.zip wp-update-breaker
	rm -rf wp-update-breaker

zip_1_1: # zip archive for version 1.1
	rm -rf wp-update-breaker
	rm -rf wp-update-breaker.1.1.zip
	cp -r v1.1 wp-update-breaker
	zip -r wp-update-breaker.1.1.zip wp-update-breaker
	rm -rf wp-update-breaker
