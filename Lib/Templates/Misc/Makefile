DATE=$(shell DATE)
BOOTSTRAP = ./cake.admin.generic.ctp
BOOTSTRAP_MIN = ./cake.admin.generic.min.ctp
BOOTSTRAP_LESS = ./less/bootstrap.less
LESS_COMPRESSOR ?= `which lessc`
WATCHR ?= `which watchr`

build:
	@@if test ! -z ${LESS_COMPRESSOR}; then \
		sed 's/@DATE/'"${DATE}"'/' ${BOOTSTRAP_LESS} >${BOOTSTRAP_LESS}.tmp; \
		lessc ${BOOTSTRAP_LESS}.tmp > ${BOOTSTRAP}; \
		lessc ${BOOTSTRAP_LESS}.tmp > ${BOOTSTRAP_MIN} --compress; \
		rm -f ${BOOTSTRAP_LESS}.tmp; \
		echo "Bootstrap successfully built! - `date`"; \
	else \
		echo "You must have the LESS compiler installed in order to build Bootstrap."; \
		echo "You can install it by running: npm install less -g"; \
	fi

watch:
	@@if test ! -z ${WATCHR}; then \
	  echo "Watching less files..."; \
	  watchr -e "watch('lib/.*\.less') { system 'make' }"; \
	else \
		echo "You must have the watchr installed in order to watch Bootstrap less files."; \
		echo "You can install it by running: gem install watchr"; \
	fi

.PHONY: build watch