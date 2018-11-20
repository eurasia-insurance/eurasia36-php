
PWD=$(shell pwd)

LOCAL_WEB=$(PWD)/web
LOCAL_SRC=$(PWD)/src/i18n
LOCAL_I18N=$(PWD)/web/i18n

DVOLS=-v $(LOCAL_WEB):/web -v $(LOCAL_SRC):/src -v $(LOCAL_I18N):/i18n

DIMG=lapsatech/msgfmt:latest
DRUN=docker run --rm

build: clean prepare compile

clean:
	rm -rf $(LOCAL_I18N)

prepare:
	$(DRUN) $(DVOLS) $(DIMG) xgettext
	$(DRUN) $(DVOLS) $(DIMG) msgmerge kk KZ

compile:
	$(DRUN) $(DVOLS) $(DIMG) msgfmt
