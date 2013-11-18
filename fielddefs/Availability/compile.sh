#!/bin/bash
cd ./
java -jar ~/bin/compiler.jar \
	--js source/moment.js \
	--js source/underscore.js \
	--js source/lang/* \
	--js source/clndr.js \
	--js source/jsavailability.js \
	--create_source_map listit2fd-availability.js.map \
	--source_map_format=V3 \
	--js_output_file listit2fd-availability.js
echo '//# sourceMappingURL=listit2fd-availability.js.map' >> listit2fd-availability.js
