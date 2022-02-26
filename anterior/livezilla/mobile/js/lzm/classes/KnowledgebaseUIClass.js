/****************************************************************************************
 * LiveZilla KnowledgebaseUI.js
 *
 * Copyright 2017 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function KnowledgebaseUI() {
    this.SelectedResourceTab = 0;
    this.openedResourcesFolder = ['1'];
    this.KBSearchCategories = ['ti', 't', 'text'];
    this.qrdChatPartner = '';
    this.qrdTreeDialog = {};
    this.LoadedResources = [];
    this.qrdSearchResults = [];

    this.CacheResourceList = null;
    this.ShowPreview = true;
}

KnowledgebaseUI.PreviewResourceId = null;
KnowledgebaseUI.CopyResource = null;
KnowledgebaseUI.CutResource = null;
KnowledgebaseUI.FileToUpload = null;
KnowledgebaseUI.TextEditor = null;
KnowledgebaseUI.QuickSearchReady = false;
KnowledgebaseUI.ShortCutResources = [];
KnowledgebaseUI.DraftResources = [];
KnowledgebaseUI.IsSyncing = false;
KnowledgebaseUI.IsLoading = false;
KnowledgebaseUI.CacheValid = false;

KnowledgebaseUI.prototype.CreateKBTopNode = function(caller, _context){

    if(!KnowledgebaseUI.CacheValid)
    {
        var i,that = this;
        var chatPartnerName = lzm_displayHelper.getChatPartner(_context)['name'];

        this.CacheResourceList = DataEngine.cannedResources.GetResourceList();

        that.qrdChatPartner = _context;

        //this.LoadedResources = this.CacheResourceList;

        var treeString = that.CreateKBTreeTopLevel(_context, false);
        var searchString = that.createQrdSearch(_context, false);

        var qrdTreeHtml = '<div id="qrd-tree-headline" class="lzm-dialog-headline"></div>' +
            '<div id="qrd-tree-body" class="lzm-dialog-body" onclick="removeKBContextMenu();">' +
                '<div id="qrd-tree-placeholder"></div></div>' +
            '<div id="qrd-tree-headline" class="lzm-dialog-headline2">' +
            '<span class="lzm-dialog-hl2-info" id="qrd-info"></span><span id="kb-button-line" style="padding-top:4px;float:right;">' + this.createButtonLine(caller, _context, chatPartnerName) + '</span></div>';

        $('#qrd-tree').html(qrdTreeHtml).trigger('create');

        lzm_displayHelper.createTabControl('qrd-tree-placeholder',
            [
                {name: t('All Resources'), content: treeString},
                {name: tid('search'), content: searchString}
            ],
            that.SelectedResourceTab);

        for (i=0; i<this.CacheResourceList.length; i++)
        {
            if ($('#folder-' + this.CacheResourceList[i].rid).html() == "")
            {
                $('#resource-' + this.CacheResourceList[i].rid + '-open-mark').css({background: 'none'})
            }
        }

        this.UpdateKBInfo();
        UIRenderer.resizeResources();

        for (i=0; i<that.openedResourcesFolder.length; i++)
            KnowledgebaseUI.HandleResourceClickEvents(that.openedResourcesFolder[i], true, false, _context);

        $('#search-qrd').keyup(function(e) {
            lzm_chatDisplay.searchButtonUp('qrd', this.CacheResourceList, e, false);
        });
        $('#search-resource').keyup(function(e) {
            lzm_chatDisplay.searchButtonUp('qrd-list', this.CacheResourceList, e, false);
        });
        $('.qrd-search-by').change(function() {
            setTimeout('lzm_chatDisplay.resourcesDisplay.fillQrdSearchList("'+that.qrdChatPartner+'", false);',10);
        });
        $('#search-resource-icon').click(function() {
            $('#search-resource').val('');
            $('#search-resource').keyup();
        });
        $('.qrd-tree-placeholder-tab').click(function() {

            var oldSelectedTabNo = that.SelectedResourceTab;
            that.SelectedResourceTab = parseInt($(this).data('tab-no'));
            $('#kb-button-line').html(that.createButtonLine(caller, _context, chatPartnerName));
            if (oldSelectedTabNo != that.SelectedResourceTab) {
                var newSelectedResource = lzm_chatDisplay.tabSelectedResources[that.SelectedResourceTab];
                lzm_chatDisplay.tabSelectedResources[oldSelectedTabNo] = lzm_chatDisplay.selectedResource;
                KnowledgebaseUI.HandleResourceClickEvents(newSelectedResource, true, false, _context);
            }
            if (that.SelectedResourceTab != 0)
                $('#add-qrd').addClass('ui-disabled');


            UIRenderer.resizeResources();
        });

        this.UpdateSelectedNodeUI();
    }
    KnowledgebaseUI.CacheValid = true;
};

KnowledgebaseUI.prototype.UpdateSelectedNodeUI = function(){
    var res = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    if(res != null)
    {
        if(res.ty != 0)
        {
            KnowledgebaseUI.HandleResourceClickEvents(lzm_chatDisplay.selectedResource,false);
        }
    }
};

KnowledgebaseUI.prototype.createButtonLine = function(caller){

    var html = '',sendButton=null,toChat='';
    var chatObj = DataEngine.ChatManager.GetChat();
    var isChatPartner = (chatObj != null && ((chatObj.GetStatus() != Chat.Closed && chatObj.GetMember(DataEngine.myId) != null) || chatObj.Type != Chat.Visitor));

    if(isChatPartner)
    {
        sendButton = lzm_inputControls.createButton('send-qrd-preview', 'lzm-button-b-active ui-disabled qrd-change-buttons', 'sendQrdPreview(\'\', \'' + chatObj.SystemId + '\');',t('To <!--chat-partner-->',[['<!--chat-partner-->',chatObj.GetName()]]), '', 'lr',{'margin-left': '2px'}, '', 30, '');
        toChat = chatObj.SystemId;
    }
    if (caller == 'view-select-panel')
    {
        if(sendButton!=null)
            html += sendButton;

        html += lzm_inputControls.createButton('add-qrd', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.ShowKBAddMenu(event);', tid('resource_add'), '<i class="fa fa-plus"></i>', 'lr', {'margin-right': '4px'},'',-1,'e');
        html += lzm_inputControls.createButton('edit-qrd', 'ui-disabled qrd-change-buttons', 'editQrd();', tid('resource_edit2'), '<i class="fa fa-edit"></i>', 'lr', {'margin-left': '0'},'',-1,'e');
        html += lzm_inputControls.createButton('show-qrd-settings', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.InitKBEntrySettings(null,\'\', \'qrd-tree\');', tid('settings'), '<i class="fa fa-gears"></i>', 'lr', {'margin-left': '-1px'},'',-1,'e');
        html += lzm_inputControls.createButton('delete-qrd', 'ui-disabled qrd-change-buttons', 'deleteQrd();', tid('resource_remove'), '<i class="fa fa-remove"></i>', 'lr', {'margin-left': '-1px','margin-right': '4px'},'',-1,'e');

        if(this.SelectedResourceTab==0)
            html += lzm_inputControls.createButton('sync-kb', '', 'KnowledgebaseUI.Synchronize();', tid('synchronize'), '<i class="fa fa-refresh"></i>', 'lr', {'margin-right': '4px'},'',-1,'e');
    }
    else
    {
        if(sendButton!=null)
            html += sendButton;

        html += lzm_inputControls.createButton('cancel-qrd', '', 'cancelQrd(\'\',\''+toChat+'\');', tid('cancel'), '', 'lr', {'margin-left': '5px'},'',30,'d');
    }
    return html;
};

KnowledgebaseUI.prototype.UpdateKBInfo = function(_count){

    if(!d(_count))
        _count = (d(this.CacheResourceList) && this.CacheResourceList != null) ? this.CacheResourceList.length : 0;

    if(KnowledgebaseUI.IsSyncing)
        $('#qrd-info').html('<b>'+tidc('loading',' ...')+'</b> (' + tid('total_entries',[['<!--total-->',_count]])+')');
    else
    {
        $('#qrd-info').html(tid('total_entries',[['<!--total-->',_count]]));
    }

    if(lzm_chatDisplay.windowWidth<500)
        $('#qrd-info').css({display:'none'});
};

KnowledgebaseUI.prototype.CreateKBTreeDialog = function(resources, _context, menuEntry, _ticketWindow) {

    var i,that = this;
    that.qrdChatPartner = _context;
    var saveResourceSelector = (_context.indexOf('TICKET SAVE') != -1);
    var loadResourceSelector = (_context.indexOf('TICKET LOAD') != -1);
    var hostElementId = _context.split('~')[1];

    if(resources == null)
    {
        this.CacheResourceList = DataEngine.cannedResources.GetResourceList();
        that.LoadedResources = this.CacheResourceList;
    }
    else
    {
        that.LoadedResources = this.CacheResourceList;
    }

    menuEntry = (typeof menuEntry != 'undefined') ? menuEntry : '';

    $('#qrd-tree-body').data('chat-partner', _context);
    $('#qrd-tree-body').data('in-dialog', true);

    var chatObj,closeToTicket = '',closeToChat='',storedDialogImage = '';
    if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
    {
        chatObj = DataEngine.ChatManager.GetChat();
        closeToChat = chatObj.SystemId;
    }
    else
    {
        closeToTicket = hostElementId;
        storedDialogImage = '<i class="fa fa-envelope"></i>';
    }

    var sbTitle = (!saveResourceSelector) ? t('Save Resource'): tid('select_folder');
    var footerString = '';

    if (d(_context))
    {
        if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
            footerString += lzm_inputControls.createButton('d-send-qrd-preview', 'ui-disabled qrd-change-buttons', 'sendQrdPreview(\'\', \'' + _context + '\');',t('To <!--chat-partner-->',[['<!--chat-partner-->',chatObj.GetName()]]), '', 'lr',{'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else if (_context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
            footerString +=  lzm_inputControls.createButton('insert-qrd-preview', 'ui-disabled qrd-change-buttons', 'KnowledgebaseUI.InsertIntoTicket(\'' + closeToTicket + '\');',t('Insert Resource'), '', 'lr', {'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else if (_context.indexOf('ATTACHMENT') == -1)
            footerString +=  lzm_inputControls.createButton('add-or-edit-qrd', 'ui-disabled qrd-change-buttons', 'addOrEditResourceFromTicket(\'' + closeToTicket + '\');',sbTitle, '', 'lr', {'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
        else
            footerString +=  lzm_inputControls.createButton('add-qrd-attachment', 'ui-disabled qrd-change-buttons', 'addQrdAttachment(\'' + closeToTicket + '\');',t('Attach Resource'), '', 'lr',{'margin-left': '5px', 'margin-top': '-5px'},'',30,'d');
    }

    footerString +=  lzm_inputControls.createButton('cancel-qrd', '', 'cancelQrd(\'' + closeToTicket + '\',\'' + closeToChat + '\');', t('Cancel'), '', 'lr', {'margin-left': '5px'},'',30,'d');

    var treeString = that.CreateKBTreeTopLevel(_context, true);
    var searchString = (!saveResourceSelector) ? that.createQrdSearch(_context, true) : '';

    var dialogData = {'exceptional-img': storedDialogImage};

    if (_context.indexOf('TICKET LOAD') == -1 && _context.indexOf('TICKET SAVE') == -1 && _context.indexOf('ATTACHMENT') == -1)
        dialogData = {'chat-partner': chatObj.SystemId, 'chat-partner-name': chatObj.GetName(), 'chat-partner-userid': chatObj.v};

    if (_context.indexOf('ATTACHMENT') != -1 || _context.indexOf('TICKET LOAD') != -1 || _context.indexOf('TICKET SAVE') != -1)
        dialogData.menu = menuEntry

    var headerString = (saveResourceSelector) ? tid('select_folder') : tid('knowledgebase');
    var bodyString = '<div id="qrd-tree-dialog-placeholder"></div>';

    var taskBarIndex = (_ticketWindow != null) ? _ticketWindow.TaskBarIndex : null;
    var dialogId = lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'database','qrd-tree-dialog-'+md5(hostElementId),'qrd-tree-dialog-'+md5(hostElementId),'cancel-qrd',true, dialogData, true, taskBarIndex);
    var tabList = [];

    tabList.push({name: t('All Resources'), content: treeString});
    if(!saveResourceSelector)
    {
        tabList.push({name: t('Quick Search'), content: searchString});
    }
    else
        tabList[0].name = tidc('select_folder','...');

    var selectedTab = that.SelectedResourceTab;

    if(saveResourceSelector)
        selectedTab = 0;
    else if(loadResourceSelector)
        selectedTab = 1;

    lzm_displayHelper.createTabControl('qrd-tree-dialog-placeholder', tabList, selectedTab);

    $('.qrd-tree-dialog-placeholder-content').css({height: ($('#qrd-tree-dialog-'+md5(hostElementId)+'-body').height() - 28) + 'px'});
    var resultListHeight = $('#qrd-tree-dialog-body').height() - $('#d-search-input').height() - 89;
    $('#d-search-results').css({'min-height': resultListHeight + 'px'});
    $('#all-resources-dialog').css({'min-height': ($('#qrd-tree-dialog-body').height() - 62) + 'px'});

    that.PopulateKBTree(this.CacheResourceList, _context, true);

    for (i=0; i<this.CacheResourceList.length; i++)
        if ($('#d-folder-' + this.CacheResourceList[i].rid).html() == "")
            $('#d-resource-' + this.CacheResourceList[i].rid + '-open-mark').css({background: 'none'});

    KnowledgebaseUI.HandleResourceClickEvents('1', true, false, _context);

    $('#d-search-resource').keyup(function(e) {
        lzm_chatDisplay.searchButtonUp('qrd-list', this.CacheResourceList, e, true);
    });
    $('.qrd-d-search-by').change(function() {
        setTimeout('lzm_chatDisplay.resourcesDisplay.fillQrdSearchList("'+that.qrdChatPartner+'", true);',10);
    });
    $('#d-search-resource-icon').click(function() {
        $('#d-search-resource').val('');
        $('#d-search-resource').keyup();
    });
    $('.qrd-tree-dialog-placeholder-tab').click(function() {
        var oldSelectedTabNo = that.SelectedResourceTab;
        UIRenderer.resizeResources();
        that.SelectedResourceTab = parseInt($(this).data('tab-no'));
        if (oldSelectedTabNo != that.SelectedResourceTab) {
            var newSelectedResource = lzm_chatDisplay.tabSelectedResources[that.SelectedResourceTab];
            lzm_chatDisplay.tabSelectedResources[oldSelectedTabNo] = lzm_chatDisplay.selectedResource;
            KnowledgebaseUI.HandleResourceClickEvents(newSelectedResource, true, false, _context);
        }
    });

    if(selectedTab==1)
        $('#d-search-resource').focus();
    UIRenderer.resizeResources();
    return dialogId;
};

KnowledgebaseUI.prototype.PopulateKBTree = function(resources, chatPartner, inDialog) {
    var sid = (inDialog) ? 'd-' : '';
    KnowledgebaseUI.PopulateFolder('1',sid);
};

KnowledgebaseUI.prototype.fillQrdSearchList = function(_caller, inDialog) {
    var that = this, searchCategories =  ['ti', 't', 'text'];

    that.KBSearchCategories = [];
    var sid = (inDialog) ? 'd-' : '';

    for (var i=0; i<searchCategories.length; i++)
    {
        if ($('#'+sid+'search-by-' + searchCategories[i]).attr('checked') == 'checked') {
            that.KBSearchCategories.push(searchCategories[i]);
        }
    }

    var searchString = $('#'+sid+'search-resource').val();
    if(d(searchString))
        searchString = $('#'+sid+'search-resource').val().replace(/^ */, '').replace(/ *$/, '');
    else
        searchString = '';

    $('#'+sid+'search-result-table').children('tbody').html(that.CreateQrdSearchResults(searchString, _caller, inDialog));
};

KnowledgebaseUI.prototype.highlightSearchResults = function(resources, isNewSearch) {
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var that = this;
    if (isNewSearch) {
        var searchString = $('#search-qrd').val().replace(/^ */, '').replace(/ *$/, '').toLowerCase();
        if (searchString != '') {
            var i, j;
            that.qrdSearchResults = [];
            for (i=0; i<resources.length; i++) {
                if (resources[i].text.toLowerCase().indexOf(searchString) != -1 ||
                    resources[i].ti.toLowerCase().indexOf(searchString) != -1) {
                    that.qrdSearchResults.push(resources[i]);
                }
            }
        }
        else
            that.qrdSearchResults = [];
    }

    if (isNewSearch) {
        var openedResourceFolders = that.openedResourcesFolder;
        $('.resource-div').css({'background-color': '#FFFFFF', color: '#000000'});
        for (i=0; i<openedResourceFolders.length; i++) {
            KnowledgebaseUI.OpenOrCloseFolder(openedResourceFolders[i], false, true);
        }
    }
    for (i=0; i<that.qrdSearchResults.length; i++) {
        $('#'+sid+'resource-' + that.qrdSearchResults[i].rid).css({'background-color': '#FFFFC6', color: '#000000', 'border-radius': '4px'});
        var parentId = that.qrdSearchResults[i].pid, counter = 0;
        if (isNewSearch) {
            while (parentId != 0 && counter < 1000) {
                for (j=0; j<resources.length; j++) {
                    if(resources[j].ty == 0 && resources[j].rid == parentId) {
                        KnowledgebaseUI.OpenOrCloseFolder(resources[j].rid, true, true);
                        parentId = resources[j].pid;
                    }
                }
                counter++;
            }
        }
    }
};

KnowledgebaseUI.prototype.editQrd = function(resource, ticketId, inDialog) {

    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;
    ticketId = (typeof ticketId != 'undefined') ? ticketId : '';

    var headerString = (resource != null && resource.ti.length) ? lzm_commonTools.SubStr(resource.ti,20,true) : t('Edit Resource');
    var footerString = '';

    if (!inDialog)
    {
        footerString += '<span style="float: left;">';
        footerString += lzm_inputControls.createButton('edited-qrd-settings', '', 'KnowledgebaseUI.InitKBEntrySettings(null,'+resource.ty+',\'edit-resource\');', t('Settings'), '<i class="fa fa-gears"></i>', 'lr',{'margin-left': '4px'},'',30,'d');
        footerString += '</span>';
    }

    footerString += lzm_inputControls.createButton('save-edited-qrd', '', '', t('Ok'), '', 'lr',{'margin-left': '4px'},'',30,'d') +
                    lzm_inputControls.createButton('cancel-edited-qrd', '', '', tid('cancel'), '', 'lr',{'margin-left': '4px'},'',30,'d');

    var qrdEditFormString = '<div id="edit-resource" class="lzm-fieldset"' +
        ' data-is_public="' + resource.p + '" data-full_text_search="' + resource.f + '" data-shorcut_word="' + resource.s + '"' +
        ' data-allow_bot="' + resource.ba + '" data-languages="' + resource.l + '" data-tags="' + resource.t + '">' +
        '<div id="edit-resource-inner">' +
        '<div id="qrd-edit-title-div" class="top-space-half qrd-edit-resource qrd-edit-html-resource qrd-edit-folder-resource qrd-edit-link-resource" style="margin-top: 0;">'
        + lzm_inputControls.createInput('qrd-edit-title', 'resource-input-new', lzm_commonTools.escapeHtml(resource.ti), tidc('title'), '', 'text', '') +
        '</div><div class="qrd-edit-resource qrd-edit-html-resource top-space" id="qrd-edit-text-div"><label for="qrd-edit-text">' + tidc('text') + '</label>' +
        '<div id="qrd-edit-text-inner">';

    qrdEditFormString += '<div id="qrd-edit-text-controls">' + lzm_inputControls.CreateInputControlPanel('basic','',true).replace(/lzm_chatInputEditor/g,'KnowledgebaseUI.TextEditor') + '</div>';
    qrdEditFormString += '<div id="qrd-edit-text-body"><textarea id="qrd-edit-text"></textarea></div></div></div>';

    if(resource.ty == 2)
        qrdEditFormString += '<div class="qrd-edit-resource qrd-edit-link-resource top-space">' + lzm_inputControls.createInput('qrd-edit-url', 'resource-input-url-new', resource.text.replace(/\"/g,''), tidc('url'), '', 'text', '') + '</div>';

    qrdEditFormString += '</div></div>';

    var dialogData = {editors: [{id: 'qrd-edit-text', instanceName: 'KnowledgebaseUI.TextEditor'}], 'resource-id': resource.rid,menu: t('Edit Resource <!--resource_title-->',[['<!--resource_title-->', resource.ti]])};

    if (ticketId != '')
        dialogData['exceptional-img'] = '<i class="fa fa-envelope"></i>';

    if (inDialog)
    {
        var taskbarIndex = TaskBarManager.GetActiveWindow().TaskBarIndex;
        TaskBarManager.RemoveActiveWindow();
        lzm_commonDialog.CreateDialogWindow(headerString, qrdEditFormString, footerString, 'database', 'qrd-edit-'+md5(ticketId), 'qrd-edit-'+md5(ticketId), 'cancel-edited-qrd', true, dialogData, true, taskbarIndex);
    }
    else
    {
        lzm_commonDialog.CreateDialogWindow(headerString, qrdEditFormString, footerString, 'database', 'qrd-edit-'+md5(resource.rid), 'qrd-edit-'+md5(resource.rid), 'cancel-edited-qrd', true, dialogData, true /*resource.ty == 1*/);
    }

    this.StoreResourceValuesInNode('edit-resource',resource.p,resource.f,resource.s,resource.ba,resource.l,resource.t,resource.rid,resource.oid,resource.g);

    UIRenderer.resizeEditResources();
};

KnowledgebaseUI.prototype.ShowKBEntrySettings = function(_resource, editorText, caller) {

    var that = this,disabled,winObj,i,headerString = tid('settings');
    var footerString = lzm_inputControls.createButton('save-qrd-settings', '', '', t('Ok'), '', 'lr', {'margin-left': '4px'}, '', 30,'d') + lzm_inputControls.createButton('cancel-qrd-settings', '', '', t('Cancel'), '', 'lr', {'margin-left': '4px'}, '', 30,'d');
    var bodyString = '<div id="qrd-settings-placeholder"></div>';

    var entryIsPublic = (_resource.p == 1 || _resource.isPublic == 1) ? ' checked="checked"' : '';
    var entryUsedByBots = (_resource.ba == 1 || _resource.allowBotAccess == 1) ? ' checked="checked"' : '';
    var useFullTextSearch = (_resource.f == 1 || _resource.fullTextSearch == 1) ? ' checked="checked"' : '';

    var tagsText = (d(_resource.t)) ? _resource.t : '';
    var shortcutText = (d(_resource.s)) ? _resource.s : (d(_resource.shortcutWord)) ? _resource.shortcutWord : '';
    var languageText = (d(_resource.l)) ? _resource.l : (d(_resource.languages)) ? _resource.languages : '';

    // id
    var rid = (typeof _resource.rid != 'undefined') ? _resource.rid : '';
    disabled = (d(_resource.draft)) ? 'ui-disabled' : '';
    var knbContent = '<fieldset class="lzm-fieldset" id="qrd-knb-id"><legend>' + tid('entry') + '</legend>' + lzm_inputControls.createInput('qrd-knb-id-text', disabled, rid, t('ID:'), '', 'text', '') + '</fieldset>';

    // owner operator
    var ownerOPArray = [], ownerGRArray = [];
    var operators = DataEngine.operators.getOperatorList('name','',true,false);

    for (i=0; i<operators.length; i++)
        ownerOPArray.push({value: operators[i].id, text: operators[i].name});

    ownerGRArray.push({value: '', text: '-'});
    var groups = DataEngine.groups.getGroupList('id',true,false);
    for (i=0; i<groups.length; i++)
        ownerGRArray.push({value: groups[i].id, text: groups[i].id});

    knbContent += '<fieldset class="lzm-fieldset" id="qrd-knb-owner-fs"><legend>' + tid('owner') + '</legend>';
    knbContent += '<label for="qrd-knb-owner-op-id-fs">' + tid('operator2') + '</label>' + lzm_inputControls.createSelect("qrd-knb-owner-op-id-fs", 'bottom-space', '', '', {position: 'right', gap: '0px'}, {}, tid('operator2'), ownerOPArray, _resource.oid, '');

    if(_resource.ty == 0)
    {
        var groupDisabeld = (lzm_commonPermissions.permissions.resources_write < 3) ? 'ui-disabled' : '';
        knbContent += '<label for="qrd-knb-owner-gr-id-fs">' + tidc('group') + '</label>' + lzm_inputControls.createSelect("qrd-knb-owner-gr-id-fs", groupDisabeld, '', '', {position: 'right', gap: '0px'}, {}, tid('group2'), ownerGRArray, _resource.g, '');
    }

    knbContent += '</fieldset>';

    knbContent += '<fieldset class="lzm-fieldset" id="qrd-knb-pub-acc-fs"><legend>' + t('Public Access') + '</legend><div><input type="checkbox" class="checkbox-custom" id="qrd-knb-pub-entry"' + entryIsPublic + ' /><label for="qrd-knb-pub-entry" class="checkbox-custom-label">' + tid('public_knb') + '</label></div>';
    var botsDisabled = (_resource.ty == 0) ? 'ui-disabled ' : '';
    knbContent += '<div class="' + botsDisabled + '"><input type="checkbox" class="checkbox-custom" id="qrd-knb-pub-bot"' + entryUsedByBots + ' /><label for="qrd-knb-pub-bot" class="checkbox-custom-label">' + t('Bots will use this resource (Virtual Assistance)') + '</label></div></fieldset>';

    if(KnowledgebaseUI.ResourceIsFile(_resource))
    {
        knbContent += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-direct-access-fs"><legend>Download</legend>';
        knbContent += '<div>'+lzm_inputControls.createInput('qrd-knb-access-url', '', KnowledgebaseUI.GetFileURL(_resource), 'Download URL:', '', 'text', '') +'</div>';
        knbContent += '</fieldset>';
    }
    else if(_resource.ty>0)
    {
        knbContent += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-direct-access-fs"><legend>' + tid('direct_access') + '</legend>';
        knbContent += '<div>'+lzm_inputControls.createInput('qrd-knb-access-url', '', KnowledgebaseUI.GetAccessUrl(_resource), tid('direct_access')+' URL:', '', 'text', '') +'</div>';
        knbContent += '</fieldset>';
    }

    var fulltextDisabled = (_resource.ty == 0) ? ' class="ui-disabled"' : '';
    knbContent += '<fieldset class="lzm-fieldset" id="qrd-knb-search-fs"><legend>' + t('Search') + '</legend><div' + fulltextDisabled + '><input type="checkbox" class="checkbox-custom" id="qrd-knb-search-full"' + useFullTextSearch + ' /><label for="qrd-knb-search-full" class="checkbox-custom-label">' + t('Fulltext Search (slower)') + '</label></div></fieldset>';

    var shortcutDisabeld = (_resource.ty == 0) ? 'ui-disabled' : '';
    knbContent += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-shortcuts-fs"><legend>' + t('Shortcuts') + '</legend>' + lzm_inputControls.createInput('qrd-knb-shortcuts-text', shortcutDisabeld, shortcutText, t('Shortcut (word, global)'), '<span class="icon-blue" style="font-weight:bold;">/</span>', 'text', 'a') + '<div class="top-space-half lzm-info-text">' + t('Example: /welcome') + '</div></fieldset>';
    knbContent += '<fieldset class="lzm-fieldset top-space" id="qrd-knb-language-fs"><legend>' + t('Language') + '</legend>';
    knbContent += lzm_inputControls.createInput('qrd-knb-language-text', '', languageText, t('Language (leave blank for all)'), '', 'text', '');
    knbContent += '<div class="top-space-half lzm-info-text">' + t('ISO 639-1 twoletter, comma-separated, case insensitive, example: en, it, fr') + '</div>';
    knbContent += '</fieldset>';

    var tagsDisabled = (_resource.ty == 0) ? ' class="ui-disabled"' : '';
    var tagsContent = '<fieldset class="lzm-fieldset" id="qrd-tags-fs"><legend>' + t('Tags') + '</legend><textarea' + tagsDisabled + ' id="qrd-tags-input">' + tagsText + '</textarea></fieldset>';
    var tabArray = [{name: tid('knowledgebase'), content: knbContent}, {name: t('Tags'), content: tagsContent}];
    var dialogData = {}, dialogId = '', tabControlWidth = 0;

    if (caller == 'qrd-tree')
    {
        dialogData = {'resource-id': _resource.rid};
        headerString = tid('settings') + ' (' + lzm_commonTools.SubStr(_resource.ti,15,true) + ')';
        lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'gears', 'qrd-settings-'+md5(_resource.rid), 'qrd-settings-'+md5(_resource.rid), 'cancel-qrd-settings', true, dialogData);
        tabControlWidth = $('#'+'qrd-settings-'+md5(_resource.rid)+'-body').width();
    }
    else if (caller == 'add-resource')
    {
        winObj = TaskBarManager.GetActiveWindow();
        dialogData = {editors: [{id: 'qrd-add-text', instanceName: 'KnowledgebaseUI.TextEditor', text: editorText}], 'resource-id': _resource.rid};
        dialogId = winObj.DialogId;
        tabControlWidth = $('#qrd-add-body').width();
        lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'gears', dialogId + '_settings',dialogId + '_settings', 'cancel-qrd-settings', true,dialogData,true,winObj.TaskBarIndex);
    }
    else if (caller == 'edit-resource')
    {
        winObj = TaskBarManager.GetActiveWindow();
        dialogData = {editors: [{id: 'qrd-edit-text', instanceName: 'KnowledgebaseUI.TextEditor', text: editorText}], 'resource-id': _resource.rid,menu: t('Edit Resource <!--resource_title-->',[['<!--resource_title-->', _resource.ti]])};
        dialogId = winObj.DialogId;
        tabControlWidth = $('#'+dialogId+'-body').width();
        headerString = tid('settings') + ' (' + lzm_commonTools.SubStr(_resource.ti,15,true) + ')';
        lzm_commonDialog.CreateDialogWindow(headerString, bodyString, footerString, 'gears', dialogId + '_settings',dialogId + '_settings', 'cancel-qrd-settings', true,dialogData,true,winObj.TaskBarIndex);
    }
    lzm_inputControls.createTabControl('qrd-settings-placeholder', tabArray, 0, tabControlWidth);
    UIRenderer.resizeResourceSettings();

    if(!KnowledgebaseUI.ResourceIsFile(_resource))
    {
        $('#qrd-knb-pub-entry').change(function() {
            if($('#qrd-knb-pub-entry').prop('checked') || $('#qrd-knb-pub-bot').prop('checked'))
                $('#qrd-knb-access-url').removeClass('ui-disabled');
            else
                $('#qrd-knb-access-url').addClass('ui-disabled');
        });
        $('#qrd-knb-pub-bot').change(function() {
            $('#qrd-knb-pub-entry').change();
        });
    }
    $('#qrd-knb-pub-entry').change();

    $('#qrd-knb-id-text').change(function(){
        $('#qrd-knb-id-text').val($('#qrd-knb-id-text').val().replace(/[^a-z0-9\-\_]/g,''));
    });

    $('#cancel-qrd-settings').click(function(){
        if (caller == 'qrd-tree')
        {
            TaskBarManager.RemoveActiveWindow();
        }
        else if (caller == 'add-resource')
        {
            TaskBarManager.RemoveActiveWindow();
            winObj = TaskBarManager.GetWindow(dialogId);
            if(winObj != null)
            {
                winObj.ShowInTaskBar = true;
                winObj.Maximize();
            }
        }
        else if (caller == 'edit-resource')
        {
            TaskBarManager.RemoveActiveWindow();
            var winObj = TaskBarManager.GetWindow(dialogId);
            if(winObj != null)
            {
                winObj.ShowInTaskBar = true;
                winObj.Maximize();
            }
        }
    });
    $('#save-qrd-settings').click(function() {

        var alertMessage = '';
        var id = $('#qrd-knb-id-text').val();
        var isPublic = $('#qrd-knb-pub-entry').prop('checked') ? '1' : '0';
        var allowBot = $('#qrd-knb-pub-bot').prop('checked') ? '1' : '0';
        var fullTextSearch = $('#qrd-knb-search-full').prop('checked') ? '1' : '0';
        var tags = $('#qrd-tags-input').val();
        var shortcutWord = $('#qrd-knb-shortcuts-text').val();
        var languages = $('#qrd-knb-language-text').val().replace(/ +/g, '');
        var ownerOPId = $('#qrd-knb-owner-op-id-fs').val();
        var ownerGRId = (_resource.ty == 0) ? $('#qrd-knb-owner-gr-id-fs').val() : '';

        if(id.length < 6)
            alertMessage = tid('invalid_id');

        if(alertMessage.length > 0){
            lzm_commonDialog.createAlertDialog(alertMessage, [{id: 'ok', name: t('Ok')}]);
            $('#alert-btn-ok').click(function() {
                lzm_commonDialog.removeAlertDialog();
            });
        }
        else
        {
            $('#cancel-qrd-settings').click();
            if (caller == 'qrd-tree' || true)
            {
                var newRes = {
                    rid: _resource.rid,
                    pid: _resource.pid,
                    ra: _resource.ra,
                    ti: _resource.ti,
                    ty: _resource.ty,
                    text: _resource.text,
                    si: _resource.si,
                    oid: ownerOPId,
                    g: ownerGRId,
                    t: tags,
                    di: 0,
                    isPublic: isPublic,
                    fullTextSearch: fullTextSearch,
                    shortcutWord: shortcutWord,
                    allowBotAccess: allowBot,
                    languages: languages,
                    new_id: id
                };

                if(d(_resource.draft))
                {
                    newRes.draft = true;
                    KnowledgebaseUI.DraftResources[_resource.rid] = lzm_commonTools.clone(newRes);
                }
                else
                {
                    CommunicationEngine.PollServerResource({First:newRes}, "set");
                    _resource.p = isPublic;
                    $('#resource-' + _resource.rid + '-icon-and-text').html(lzm_chatDisplay.resourcesDisplay.GetResourceIconHTML(_resource) + '<span class="qrd-title-span">'+lzm_commonTools.htmlEntities(_resource.ti)+'</span>');
                }
            }

            if (caller == 'add-resource' || caller == 'edit-resource')
            {
                setTimeout(function(){
                    that.StoreResourceValuesInNode(caller,isPublic,fullTextSearch,shortcutWord,allowBot,languages,tags,id,ownerOPId,ownerGRId);
                },400);
            }
        }
    });
};

KnowledgebaseUI.prototype.AddQrd = function(_newType, _newResourceId, _parentResource, ticketId, inDialog, toAttachment, sendToChat, menuEntry, _windowObj) {

    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;
    toAttachment = (typeof toAttachment != 'undefined') ? toAttachment : false;
    sendToChat = (typeof sendToChat != 'undefined') ? sendToChat : null;
    menuEntry = (typeof menuEntry != 'undefined') ? menuEntry : '';
    ticketId = (typeof ticketId != 'undefined') ? ticketId : '';
    _windowObj = (d(_windowObj)) ? _windowObj : null;

    var headerString = '';

    if (toAttachment)
        headerString = tid('add_attachment');
    else if(sendToChat != null)
        headerString = (sendToChat.type == 'link') ? tid('send_url') : tid('send_file');
    else
        headerString = t('Add new Resource');

    var footerString = '',okDisabled = '';

    if (!inDialog && _newType != 3)
    {
        footerString += '<span style="float: left;">';
        footerString += lzm_inputControls.createButton('add-qrd-settings', '', 'KnowledgebaseUI.InitKBEntrySettings(\''+_newResourceId+'\','+_newType+',\'add-resource\');', tid('settings'), '<i class="fa fa-gears"></i>', 'lr',{'margin-left': '4px'},'',30,'d');
        footerString += '</span>';
    }

    footerString +=
        lzm_inputControls.createButton('save-new-qrd', okDisabled, '', tid('ok'), '', 'lr', {'margin-left': '4px'}, '',30,'d') +
        lzm_inputControls.createButton('cancel-new-qrd', '', '', tid('cancel'), '', 'lr', {'margin-left': '4px'}, '',30,'d');

    var qrdAddFormString = '<div id="add-resource" class="lzm-fieldset" data-is_public="" data-full_text_search="" data-shorcut_word="" data-allow_bot="" data-languages="" data-tags=""><div id="add-resource-inner">';
    var isVisible = (sendToChat != null && sendToChat.type == 'link') ? ' style="display:block;"' : '';
    qrdAddFormString += '<div' + isVisible + ' id="qrd-add-title-div" class="top-space-half qrd-add-resource qrd-add-html-resource qrd-add-folder-resource qrd-add-link-resource">' + lzm_inputControls.createInput('qrd-add-title', 'resource-input-new', '', tidc('title'), '', 'text', '') + '</div>';

    qrdAddFormString += '<div id="qrd-add-text-div" class="qrd-add-resource qrd-add-html-resource"><div class="top-space"><label for="qrd-add-text">' + tidc('text') + '</label></div><div id="qrd-add-text-inner">';
    qrdAddFormString += '<div id="qrd-add-text-controls">' + lzm_inputControls.CreateInputControlPanel('basic').replace(/lzm_chatInputEditor/g,'KnowledgebaseUI.TextEditor') + '</div>';
    qrdAddFormString += '<div id="qrd-add-text-body"><textarea id="qrd-add-text"></textarea></div></div></div>';

    isVisible = (sendToChat != null && sendToChat.type == 'link') ? ' style="display:block;"' : '';
    qrdAddFormString += '<div' + isVisible + ' id="qrd-add-url-div" class="top-space qrd-add-link-resource qrd-add-resource">' + lzm_inputControls.createInput('qrd-add-url', 'resource-input-url-new', '', tidc('url'), '', 'text', '') + '</div>';

    isVisible = (sendToChat != null && sendToChat.type == 'file') ? ' style="display:block;"' : '';
    qrdAddFormString += '<div' + isVisible + ' id="qrd-add-file-div" class="qrd-add-file-resource qrd-add-resource">' +

        '<label class="file-upload-label file-drop-zone">' +
        '<i class="fa fa-cloud-upload icon-xxxl icon-light"></i>' +
        lzm_inputControls.createInput('file-upload-input', 'resource-input-new', '', '', '', 'file', '') +
        '<span></span>' +
        '<div id="file-upload-progress" style="display: none;"><span id="file-upload-numeric" class="text-xxl text-green">0%</span></div>' +
        '<div id="file-upload-name" class="text-bold text-xl"></div>' +
        '<div id="file-upload-size"></div>' +
        '<div id="file-upload-type"></div>' +
        '<div id="file-upload-error" class="text-orange text-bold"></div>' +
        '<div id="cancel-file-upload-div" style="display: none;"><br><br>' + lzm_inputControls.createButton('cancel-file-upload','', 'cancelFileUpload(event)', tid('cancel'), '', 'lr',{padding:'5px 10px;','display': 'none'}) + '</div>' +
        '</label>';

    qrdAddFormString += '<div id="file-drop-box" class="file-drop-zone"></div></div></div></div>';

    var dialogData = {editors: [{id: 'qrd-add-text', instanceName: 'KnowledgebaseUI.TextEditor'}], 'resource-id': _parentResource.rid};

    if (ticketId != '')
        dialogData['exceptional-img'] = '<i class="fa fa-envelope"></i>';

    if (inDialog)
    {
        if (toAttachment)
        {
            dialogData.menu = menuEntry;
            lzm_commonDialog.CreateDialogWindow(headerString, qrdAddFormString, footerString, 'link', toAttachment + '_attachment',toAttachment + '_attachment', 'cancel-new-qrd', true, dialogData, true, _windowObj.TaskBarSorterIndex);
        }
        else
        {
            var taskBarIndex = TaskBarManager.GetActiveWindow().TaskBarIndex;
            TaskBarManager.RemoveActiveWindow();
            lzm_commonDialog.CreateDialogWindow(headerString, qrdAddFormString, footerString, 'database', 'qrd-add-' + md5(ticketId), 'qrd-add-' + md5(ticketId), 'cancel-new-qrd', _newType == 1, dialogData, true, taskBarIndex);
        }
    }
    else
    {
        var showFullscreen = (_newType == 1 || _newType == 3);
        var dialogId = (sendToChat == null) ? 'qrd-add-'+lzm_commonTools.guid() : sendToChat.dialog_id;
        if (sendToChat != null)
            dialogData.menu = headerString;

        dialogId = lzm_commonDialog.CreateDialogWindow(headerString, qrdAddFormString, footerString, 'database', 'qrd-add', dialogId, 'cancel-new-qrd', showFullscreen, dialogData);
        $('#qrd-add').data('dialog_id', dialogId);
    }

    var dropContainer = document.getElementById('file-drop-box');
    $('#file-upload-input').change(function(){changeFile();});
    $('.file-drop-zone').on({
        dragstart: function(evt) {
            dropContainer.style.background = '#f1f1f1';
            evt.preventDefault();

        },
        dragover: function(evt) {
            dropContainer.style.background = '#f1f1f1';
            evt.preventDefault();

        },
        dragleave: function(evt) {
            dropContainer.style.background = '#fff';
            evt.preventDefault();
        },
        dragenter: function(evt) {
            dropContainer.style.background = '#f1f1f1';
            evt.preventDefault();

        },
        dragend: function(evt) {
            dropContainer.style.background = '#fff';
            evt.preventDefault();

        },
        drop: function(evt) {
            evt.preventDefault();
            changeFile(evt.originalEvent.dataTransfer.files[0]);
        }
    });

    document.body.addEventListener('drop', function(e) {
        e.preventDefault();
    }, false);

    $('#add-resource, #qrd-add-body').on({
        dragstart: function(evt) {evt.preventDefault();},
        dragover: function(evt) {evt.preventDefault();},
        dragleave: function(evt) {evt.preventDefault();},
        dragenter: function(evt) {evt.preventDefault();},
        dragend: function(evt) {evt.preventDefault();},
        drop: function(evt) {evt.preventDefault();}
    });

    //UIRenderer.resizeAddResources();

    if (ticketId != '')
        delete lzm_chatDisplay.ticketResourceText[ticketId];
};

KnowledgebaseUI.prototype.UpdateResources = function(_newResources) {

    if(lzm_chatDisplay.selected_view == 'qrd')
        this.CacheResourceList = DataEngine.cannedResources.GetResourceList();
    else
        KnowledgebaseUI.CacheValid = false;

    var key,res, parentsToUpdate=[];
    for(key in _newResources)
    {
        res = _newResources[key];
        if(res.di != 0)
        {
            // REMOVE
            if ($('#resource-' + res.rid).length != 0)
                $('#resource-' + res.rid).remove();
        }
        else
        {
            // ADD,UPDATE
            if($.inArray(res.pid,parentsToUpdate)== -1)
                parentsToUpdate.push(res.pid);
        }
    }

    for(key in parentsToUpdate)
    {
        KnowledgebaseUI.PopulateFolder(parentsToUpdate[key],'',true);
    }

    this.UpdateKBInfo();

    if(lzm_chatDisplay.selectedResource != '')
        KnowledgebaseUI.HandleResourceClickEvents(lzm_chatDisplay.selectedResource,true);
};

KnowledgebaseUI.prototype.StoreResourceValuesInNode = function(_node,isPublic,fullTextSearch,shortcutWord,allowBot,languages,tags,id,ownerOPId,ownerGRId){
    $('#' + _node).data('is_public', isPublic);
    $('#' + _node).data('full_text_search', fullTextSearch);
    $('#' + _node).data('shorcut_word', shortcutWord);
    $('#' + _node).data('allow_bot', allowBot);
    $('#' + _node).data('languages', languages);
    $('#' + _node).data('tags', tags);
    $('#' + _node).data('new_id', id);
    $('#' + _node).data('oid', ownerOPId);
    $('#' + _node).data('g', ownerGRId);
};

KnowledgebaseUI.prototype.GetResourceIconHTML = function(resource) {
    var that = this;

    var resourceIcon;
    switch(resource.ty.toString())
    {
        case '0':
            resourceIcon = '<i class="fa fa-folder"></i>';
            break;
        case '1':
            resourceIcon = '<i class="fa fa-file-text icon-gray"></i>';
            break;
        case '2':
            if (typeof resource.text != 'undefined' && resource.text.indexOf('mailto:') == 0)
                resourceIcon = '<i class="fa fa-envelope icon-blue"></i>';
            else
                resourceIcon = '<i class="fa fa-link icon-red"></i>';
            break;
        default:
            resourceIcon = that.getFileTypeIcon(resource.ti);
            break;
    }
    return resourceIcon + this.GetPublicIconHTML(resource);
};

KnowledgebaseUI.prototype.getFileTypeIcon = function(fileName) {

    fileName = (d(fileName)) ? fileName.toLowerCase() : '';

    var fileIcon = '<i class="fa fa-file"></i>';
    if (checkEnding(fileName, ['mp3', 'wav', 'ogg', 'wma']))
        fileIcon = '<i class="fa fa-file-sound-o icon-orange"></i>';
    else if (checkEnding(fileName, ['png', 'gif', 'jpg', 'bmp', 'jpeg']))
        fileIcon = '<i class="fa fa-file-picture-o icon-blue"></i>';
    else if (checkEnding(fileName, ['doc', 'docx', 'odt', 'rtf'])) {
        fileIcon = '<i class="fa fa-file-word-o icon-blue"></i>';
    } else if (checkEnding(fileName, ['xls', 'xlsx', 'ods', 'csv'])) {
        fileIcon = '<i class="fa fa-file-excel-o icon-green"></i>';
    } else if (checkEnding(fileName, ['ppt', 'pptx', 'odp'])) {
        fileIcon = '<i class="fa fa-file-powerpoint-o"></i>';
    } else if (checkEnding(fileName, ['zip', 'rar', 'tar', 'tar.gz', 'tar.bz2', 'tar.xz', 'tgz', '7z'])) {
        fileIcon = '<i class="fa fa-file-archive-o icon-red"></i>';
    } else if (checkEnding(fileName, ['pdf', 'ps'])) {
        fileIcon = '<i class="fa fa-file-pdf-o icon-red"></i>';
    } else if (checkEnding(fileName, ['exe', 'bat'])) {
        fileIcon = '<i class="fa fa-gear icon-red"></i>';
    } else if (checkEnding(fileName, ['mpg', 'mpeg', 'avi', 'mp4', 'webm', 'mov', 'ogm', 'wmf'])) {
        fileIcon = '<i class="fa fa-file-movie-o"></i>';
    } else if (checkEnding(fileName, ['js', 'php', 'html', 'css', 'py', 'sh', 'pl', 'cs', 'java', 'c', '.c++', '.cpp']))
        fileIcon = '<i class="fa fa-file-code-o"></i>';

    return fileIcon;
};

var checkEnding = function(fileName, ending) {
    ending = (typeof ending == 'string') ? [ending] : (typeof ending == 'object' && ending instanceof Array) ? ending : [];
    var fnLength = fileName.length, eLength = 0, rt = false;
    for (var i=0; i<ending.length; i++) {
        eLength = ending[i].length;
        rt = rt || (ending[i] != '' && fileName.indexOf('.' + ending[i]) == fnLength - eLength - 1);
    }
    return rt;
};

KnowledgebaseUI.prototype.GetPublicIconHTML = function(resource) {
    var html = '';
    if(d(resource.p) && resource.p.toString()=='1')
        html += '<i class="fa fa-life-ring icon-blue icon-public"></i>';
    if(d(resource.ba) && resource.ba.toString()=='1')
        html += '<i class="fa fa-microchip icon-green icon-public icon-bot"></i>';
    return html;
};

KnowledgebaseUI.prototype.CreateKBTreeTopLevel = function(_context, inDialog) {

    var topLayerResource = DataEngine.cannedResources.getResource('1');
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + topLayerResource.rid + '\',false,false,\'' + _context + '\')"';
    var onContextMenu = ' oncontextmenu="return false;"', that = this;

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + topLayerResource.rid + '\');return false;"';

    var id = (inDialog) ? 'all-resources-dialog' : 'all-resources';
    var sid = (inDialog) ? 'd-' : '';
    var plusMinusImage = '';
    var resourceHtml = '<div id="'+id+'" class="lzm-fieldset">' +
        '<div id="'+id+'-inner"><div id="'+sid+'resource-' + topLayerResource.rid + '" class="resource-div lzm-unselectable">' +
        '<span class="resource-open-mark" id="'+sid+'resource-' + topLayerResource.rid + '-open-mark"' +
        onclickAction + onContextMenu + '>' + plusMinusImage + '</span>' +
        '<span class="resource-icon-and-text" id="'+sid+'resource-' + topLayerResource.rid + '-icon-and-text"' +
        onclickAction + onContextMenu + '>' + that.GetResourceIconHTML(topLayerResource) +
        '<span class="qrd-title-span">' + lzm_commonTools.htmlEntities(topLayerResource.ti) + '</span>' +
        '</span></div><div id="'+sid+'folder-' + topLayerResource.rid + '" style="display: none;"></div>' +
        '</div></div>';

    if(!inDialog)
        resourceHtml += '<div id="all-resources-preview" class="kb-preview-box"></div>';
    else
        resourceHtml += '<div id="all-resources-preview-dialog" class="kb-preview-box"></div>';

    return resourceHtml;
};

KnowledgebaseUI.prototype.SetPreview = function(_resource){

    var pd = null;
    if($('#all-resources-preview-dialog').length)
        pd = $('#all-resources-preview-dialog');
    else if($('#all-resources-preview').length)
        pd = $('#all-resources-preview');

    if(pd != null)
    {
        KnowledgebaseUI.PreviewResourceId = null;
        pd.html('');
        if(_resource!=null)
        {
            KnowledgebaseUI.PreviewResourceId = _resource.rid;
            if(_resource.ty=='1'||_resource.ty=='0')
                pd.html(_resource.text);
            else if(_resource.ty=='2')
                pd.html('<a target="_blank" href="'+_resource.text+'">' + _resource.ti+'</a>');
            else if(_resource.ty=='3' && checkEnding(_resource.ti, ['png', 'gif', 'jpg', 'bmp', 'jpeg']))
            {
                pd.html('<img src="'+DataEngine.getServerUrl("getfile.php")+"?file=&id="+_resource.rid+'">');
            }
        }
    }
};

KnowledgebaseUI.prototype.UpdatePreview = function(){
    if(KnowledgebaseUI.PreviewResourceId != null)
    {
        var ures = DataEngine.cannedResources.getResource(KnowledgebaseUI.PreviewResourceId);
        if(ures != null)
        {
            if($('#all-resources-preview').html() != ures.text)
                this.SetPreview(ures);
        }
        else
            this.SetPreview(null);
    }
};

KnowledgebaseUI.prototype.createQrdSearch = function(chatPartner, inDialog) {
    var sid = (inDialog) ? 'd-' : '';
    var that = this, attachmentDataString = (chatPartner.indexOf('ATTACHMENT') != -1) ? ' data-attachment="1"' : ' data-attachment="0"';
    var searchHtml = '<div id="search-input">' +
        '<div class="lzm-fieldset"><table id="search-input-inner">' +
        '<tr><td colspan="2">' +
        lzm_inputControls.createInput(sid+'search-resource','', '', t('Search'), '<i class="fa fa-remove"></i>', 'text', 'b') +
        '</td>';
    var checkedString = ($.inArray('ti', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td>' +
        '<input type="checkbox" class="checkbox-custom qrd-'+sid+'search-by" id="'+sid+'search-by-ti"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-ti" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Title') + '</td></tr>';
    checkedString = ($.inArray('t', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td><input type="checkbox" id="'+sid+'search-by-t" class="checkbox-custom qrd-'+sid+'search-by"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-t" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Tags') + '</td></tr>';
    checkedString = ($.inArray('text', that.KBSearchCategories) != -1) ? ' checked="checked"' : '';
    searchHtml += '<tr><td><input type="checkbox" id="'+sid+'search-by-text" class="checkbox-custom qrd-'+sid+'search-by"' + checkedString + ' />' +
        '<label for="'+sid+'search-by-text" class="checkbox-custom-label"></label>' +
        '</td>' +
        '<td>' + t('Content') + '</td></tr>' +
        '</table></div>' +
        '</div><br>' +
        '<div id="'+sid+'search-results">' +
        '<div class="lzm-dialog-headline4"><span>' + t('Results') + '</span></div>' +
        '<div id="'+sid+'search-result-frame">' +
        '<table id="'+sid+'search-result-table" class="visible-list-table alternating-rows-table lzm-unselectable" style="width: 100%;"' + attachmentDataString + '><thead><tr>' +
        '<th></th><th>' + t('Title') + '</th><th>' + t('Tags') + '</th><th>' + t('Content') + '</th>' +
        '</tr></thead><tbody></tbody></table></div>' +
        '</div>';

    return searchHtml;
};

KnowledgebaseUI.prototype.CreateQrdSearchResults = function(searchString, _caller, inDialog) {
    var searchHtml = '', that = this;
    var sid = (inDialog) ? 'd-' : '';
    var resources = DataEngine.cannedResources.GetResourceList();
    var searchCategories = that.KBSearchCategories;
    $('#'+sid+'search-result-table').data('search-string', searchString);
    var resultIds = [];
    if (searchString != '')
    {
        for (var i=0; i<resources.length; i++)
        {
            for (var j=0; j<searchCategories.length; j++)
            {
                var contentToSearch = resources[i][searchCategories[j]].toLowerCase();
                if (resources[i].ty != 0 && contentToSearch.indexOf(searchString.toLowerCase()) != -1 && $.inArray(resources[i].rid, resultIds) == -1)
                {
                    if (resources[i].ty == 3 || resources[i].ty == 4 || $('#'+sid+'search-result-table').data('attachment') != '1')
                    {
                        searchHtml += that.CreateSearchResultLine(resources[i], searchString, _caller, inDialog);
                        resultIds.push(resources[i].rid);
                    }
                }
            }
        }
    }
    return searchHtml;
};

KnowledgebaseUI.prototype.CreateSearchResultLine = function(resource, searchString, _context, inDialog) {
    searchString = (typeof searchString != 'undefined') ? searchString : '';
    _context = (typeof _context != 'undefined') ? _context : '';
    var regExp = new RegExp(RegExp.escape(searchString), 'i'), that = this;
    var sid = (inDialog) ? 'd-' : '';
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + resource.rid + '\',false,false, \'' + _context + '\');"';
    var onDblClickAction = '', onContextMenu = ' oncontextmenu="return false;"';

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + resource.rid + '\');return false;"';

    if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
    {
        if (_context.indexOf('TICKET LOAD') != -1)
            onDblClickAction = ' ondblclick="KnowledgebaseUI.InsertIntoTicket(\'' + _context.split('~')[1] + '\');"';
        else if (_context.indexOf('ATTACHMENT') != -1)
            onDblClickAction = ' ondblclick="addQrdAttachment(\'' + _context.split('~')[1] + '\');"';
        else if (inDialog && _context != '')
            onDblClickAction = ' ondblclick="sendQrdPreview(\'' + resource.rid + '\', \'' + _context + '\');"';
        else if (parseInt(resource.ty) < 3)
            onDblClickAction = ' ondblclick="editQrd();"';
    }

    var sstyle=' style="color: #000000 !important; background-color: #fff9a9 !important;"';
    var content = ($.inArray(parseInt(resource.ty), [3,4]) == -1) ? resource.text.replace(/<.*?>/g, ' ') : '';

    if(content.length > 200)
        content = content.substring(0,200)+" ...";

    content = content.replace(regExp, '<span'+sstyle+'>' + searchString + '</span>');

    var title = lzm_commonTools.htmlEntities(resource.ti).replace(/<.*?>/g, ' ');
    if(title.length > 200)
        title = title.substring(0,200)+" ...";
    title = title.replace(regExp, '<span'+sstyle+'>' + searchString + '</span>');

    var searchLineHtml = '<tr style="cursor: pointer;" class="qrd-search-line lzm-unselectable" id="qrd-'+sid+'search-line-' + resource.rid + '"' + onclickAction + onDblClickAction + onContextMenu + '>' +
        '<td class="icon-column resource-icon-and-text">' + that.GetResourceIconHTML(resource,false) + '</td>' +
        '<td>' + title + '</td>' +
        '<td>' + resource.t + '</td>' +
        '<td>' + content + '</td>' +
        '</tr>';
    return searchLineHtml;
};

KnowledgebaseUI.prototype.GetResourceHTML = function(resource, _context, inDialog) {

    _context = (typeof _context != 'undefined') ? _context : '';
    inDialog = (typeof inDialog != 'undefined') ? inDialog : false;

    KnowledgebaseUI.ReplaceTitle(resource);

    var sid = (inDialog) ? 'd-' : '';
    var onclickAction = ' onclick="KnowledgebaseUI.HandleResourceClickEvents(\'' + resource.rid + '\',false,false,\''+_context+'\')"';
    var onDblClickAction = '', that = this;
    var onContextMenu = ' oncontextmenu="return false;"';

    if (((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) && !inDialog)
        onContextMenu = ' oncontextmenu="KnowledgebaseUI.OpenKBContextMenu(event, \'' + _context + '\', \'' + resource.rid + '\');return false;"';

    var resourceHtml = '<div id="'+sid+'resource-' + resource.rid + '" class="resource-div lzm-unselectable" style="padding-left: ' + (20 * resource.ra) + 'px;">';
    if (resource.ty == 0)
    {
        var openMarkIcon = (KnowledgebaseUI.GetChildNodes(resource.rid).length > 0) ? '<i class="fa fa-caret-right"></i>' : '';
        resourceHtml += '<span class="resource-open-mark" id="'+sid+'resource-' + resource.rid + '-open-mark"' + onclickAction + onContextMenu + '>' + openMarkIcon + '</span>';
    }
    else
    {
        resourceHtml += '<span class="resource-empty-mark"></span>';
        if ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp())
        {
            if (_context.indexOf('TICKET LOAD') != -1)
            {
                onDblClickAction = ' ondblclick="KnowledgebaseUI.InsertIntoTicket(\'' + _context.split('~')[1] + '\');"';
            }
            else if (_context.indexOf('ATTACHMENT') != -1)
            {
                onDblClickAction = ' ondblclick="addQrdAttachment(\'' + _context.split('~')[1] + '\');"';
            }
            else if (inDialog && _context != '')
            {
                onDblClickAction = ' ondblclick="sendQrdPreview(\'' + resource.rid + '\', \'' + _context + '\');"';
            }
            else if (parseInt(resource.ty) < 3)
            {
                onDblClickAction = ' ondblclick="editQrd();"';
            }
        }
    }
    resourceHtml += '<span class="resource-icon-and-text" id="'+sid+'resource-' + resource.rid + '-icon-and-text"' +
        onclickAction + onDblClickAction + onContextMenu + '>' +
        that.GetResourceIconHTML(resource) +
        '<span class="qrd-title-span">' +
        lzm_commonTools.htmlEntities(resource.ti) + '</span>' +
        '</span></div>';

    if (resource.ty == 0)
        resourceHtml += '<div id="'+sid+'folder-' + resource.rid + '" style="display: none;"></div>';


    return resourceHtml;
};

KnowledgebaseUI.prototype.createQrdTreeContextMenu = function(myObject){
    var contextMenuHtml = '', disabledClass;

    if(myObject != 'MENU')
    {
        contextMenuHtml += '<div onclick="showSubMenu(\'qrd-tree\', \'kb_add\', \'\', %CONTEXTX%, %CONTEXTY%, %MYWIDTH%, %MYHEIGHT%)"><span class="cm-line cm-click">' + tid('add') + '</span><i class="fa fa-caret-right lzm-ctxt-right-fa"></i></div><hr />';

        disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Copy();"><span class="cm-line">' + tid('copy') + '</span></div>';

        disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Cut();"><span class="cm-line">' + tid('cut') + '</span></div>';

        disabledClass = (myObject != 'MENU' && (KnowledgebaseUI.CopyResource==null&&KnowledgebaseUI.CutResource==null)) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.__Paste();"><span class="cm-line">' + tid('paste') + '</span></div><hr />';

        disabledClass = (myObject != 'MENU' && (myObject.rid == 1 || myObject.ty == 3 || myObject.ty == 4)) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="editQrd();"><span id="edit-qrd-ctxt" class="cm-line">' + t('Edit') + '</span></div>';

        disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="deleteQrd();"><span id="delete-qrd-ctxt" class="cm-line">' + t('Delete') + '</span></div><hr />';

        disabledClass = (myObject != 'MENU' && (myObject.ty < 3 || myObject.ty > 4)) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.DownloadFile();">' + '<i class="fa fa-download"></i><span id="show-qrd-download-ctxt" class="cm-line cm-line-icon-left">' + tid('save') + '</span></div><hr />';

        disabledClass = (myObject != 'MENU' && myObject.rid == 1) ? ' class="ui-disabled"' : '';
        contextMenuHtml += '<div' + disabledClass + ' onclick="KnowledgebaseUI.InitKBEntrySettings(\''+myObject.rid+'\', \'\', \'qrd-tree\');">' + '<i class="fa fa-gears"></i><span id="show-qrd-settings-ctxt" class="cm-line cm-line-icon-left">' + t('Settings') + '</span></div>';
    }
    else
    {
        contextMenuHtml += '<div onclick="addQrd(1);"><span id="add-qrd-ctxt" class="cm-line">' + tid('text') + '</span></div>';
        contextMenuHtml += '<div onclick="addQrd(2);"><span id="add-qrd-clnk" class="cm-line">' + tid('link') + '</span></div>';
        contextMenuHtml += '<div onclick="addQrd(3);"><span id="add-qrd-cfile" class="cm-line">' + tid('file') + '</span></div>';
        if(IFManager.IsDesktopApp() && d(IFManager.DeviceInterface.hasModule) && IFManager.DeviceInterface.hasModule('lz-screenshot-widget')){
            contextMenuHtml += '<div onclick="IFManager.IFScreenCast(\'knowledgebase\', \'' + lzm_chatDisplay.selectedResource + '\');lzm_chatDisplay.RemoveAllContextMenus();"><span id="add-qrd-cfile" class="cm-line">' + tid('screenshot') + '</span></div>';
        }
        contextMenuHtml += '<div onclick="addQrd(0);"><span id="add-qrd-cfld" class="cm-line">' + tid('resource_folder') + '</span></div>';
    }

    return contextMenuHtml;
};

KnowledgebaseUI.prototype.CalculateRank = function(_resource,_rank){
    _rank = (d(_rank)) ? _rank : 0;
    var p = DataEngine.cannedResources.getResource(_resource.pid);
    if(p==null)
        return _rank;
    else
        return this.CalculateRank(p,_rank+1);
};

KnowledgebaseUI.prototype.IsParentOf = function(_a,_b){

    if(_b.pid == _a.pid)
    {
        return false;
    }

    if(_b.pid == _a.rid)
    {
        return true;
    }

    var parent = DataEngine.cannedResources.getResource(_b.pid);
    if(parent == null)
        return false;
    else if(parent.rid==_a.pid && parent.rid != '1')
    {
        return true;
    }
    else
        return this.IsParentOf(_a,parent);
};

KnowledgebaseUI.__Copy = function(){
    removeKBContextMenu();
    KnowledgebaseUI.CutResource = null;
    KnowledgebaseUI.CopyResource = lzm_chatDisplay.selectedResource;
};

KnowledgebaseUI.__Cut = function(){
    removeKBContextMenu();

    if (!lzm_commonPermissions.checkUserResourceWritePermissions(lzm_chatDisplay.myId, 'edit', DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource)))
    {
        showNoPermissionMessage();
        return;
    }

    KnowledgebaseUI.CopyResource = null;
    KnowledgebaseUI.CutResource = lzm_chatDisplay.selectedResource;
};

KnowledgebaseUI.__Paste = function(){
    removeKBContextMenu();
    var actionNode=null,action='';

    if(KnowledgebaseUI.CutResource != null)
    {
        action = 'cut';
        actionNode = DataEngine.cannedResources.getResource(KnowledgebaseUI.CutResource);
        KnowledgebaseUI.CutResource = null;

    }
    else if(KnowledgebaseUI.CopyResource != null)
    {
        action = 'copy';
        actionNode = DataEngine.cannedResources.getResource(KnowledgebaseUI.CopyResource);
    }

    var targetFolder = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);

    if(targetFolder.ty != '0')
        targetFolder = DataEngine.cannedResources.getResource(targetFolder.pid);

    if (!lzm_commonPermissions.checkUserResourceWritePermissions(lzm_chatDisplay.myId, 'add', targetFolder))
    {
        showNoPermissionMessage();
        return;
    }

    if(actionNode != null && targetFolder != null)
    {
        if(actionNode.rid != targetFolder.rid || action == 'copy')
        {
            if(action == 'cut' && actionNode.ty == '0' && lzm_chatDisplay.resourcesDisplay.IsParentOf(actionNode,targetFolder))
            {
                return;
            }
            CommunicationEngine.PollServerResource({First:actionNode,Second:targetFolder},action);
        }
    }
};

KnowledgebaseUI.__ShowKBEntrySettings = function(_resourceId, caller, editorText){
    removeKBContextMenu();
    _resourceId = (_resourceId == null) ? lzm_chatDisplay.selectedResource : _resourceId;
    var resource = DataEngine.cannedResources.getResource(_resourceId);

    if (resource == null && d(KnowledgebaseUI.DraftResources[_resourceId]))
    {
        resource = KnowledgebaseUI.DraftResources[_resourceId];
    }

    if (resource == null)
    {
        resource = {t: ''};
        if (_resourceId == 'FOLDER')
            resource.ty = 0;
    }

    if (caller == 'edit-resource' || caller == 'add-resource' || _resourceId == 'TEXT_FILE_URL' ||  caller == 'qrd-tree' || _resourceId == 'FOLDER')
    {
        if(lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource))
        {
            lzm_chatDisplay.resourcesDisplay.ShowKBEntrySettings(resource, editorText, caller);
        }
        else
            showNoPermissionMessage();
    }

};

KnowledgebaseUI.RunsInDialog = function(){
    var winObj = TaskBarManager.GetActiveWindow();
    return (winObj != null);
};

KnowledgebaseUI.UpdateSelection = function(){


};

KnowledgebaseUI.InitKBEntrySettings = function(_resourceId,_type,_caller){
    var editorText = (_type == 1) ? KnowledgebaseUI.TextEditor.grabHtml() : '';
    KnowledgebaseUI.__ShowKBEntrySettings(_resourceId, _caller, editorText);
};

KnowledgebaseUI.DownloadFile = function(){
    removeKBContextMenu();
    var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    openLink(KnowledgebaseUI.GetFileURL(resource));
};

KnowledgebaseUI.GetFileURL = function(_resource){
    var thisServer = CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url;
    return thisServer + '/getfile.php?file=' + _resource.ti + '&id=' + _resource.rid;
};

KnowledgebaseUI.ResourceIsFile = function(_resource){
    return $.inArray(_resource.ty, ['2', '3', '4']) != -1
};

KnowledgebaseUI.GetAccessUrl = function(_resource){
    if(!KnowledgebaseUI.ResourceIsFile(_resource))
        return DataEngine.serverProtocol + DataEngine.serverUrl.replace(':80/','/').replace(':443/','/') + '/knowledgebase.php?article=' + lzm_commonTools.htmlEntities(_resource.rid);
    else
        return KnowledgebaseUI.GetFileURL(_resource);
};

KnowledgebaseUI.ShowKBAddMenu = function(e){
    KnowledgebaseUI.OpenKBContextMenu(e,'LIST','MENU');
    e.stopPropagation();
};

KnowledgebaseUI.OpenKBContextMenu = function(e, _context, resourceId){

    if(resourceId != 'MENU')
    {
        lzm_chatDisplay.selectedResource = resourceId;
        KnowledgebaseUI.HandleResourceClickEvents(resourceId, true, !IFManager.IsMobileOS, _context);
    }
    else if(lzm_chatDisplay.selectedResource != '')
    {
        var sel = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
        if(sel != null)
        {
            //if(sel.ty == 0)
              //  KnowledgebaseUI.OpenOrCloseFolder(sel.rid, true, true);
        }
    }
    var resource = (resourceId != 'MENU') ? DataEngine.cannedResources.getResource(resourceId) : 'MENU';
    var scrolledDownY = (resourceId != 'MENU') ? $('#qrd-tree-body').scrollTop() : 15;
    var scrolledDownX = (resourceId != 'MENU') ? $('#qrd-tree-body').scrollLeft() : -15;
    var parentOffset = $('#qrd-tree-body').offset();
    var yValue = e.pageY - parentOffset.top;
    var xValue = e.pageX - parentOffset.left;
    if (resource != null)
    {
        resource.chatPartner = _context;
        lzm_chatDisplay.showContextMenu('qrd-tree', resource, xValue + scrolledDownX, yValue + scrolledDownY);
        e.preventDefault();
    }
};

KnowledgebaseUI.HandleResourceClickEvents = function(resourceId, onlyOpenFolders, _rightClick, _context){
    removeKBContextMenu();

    onlyOpenFolders = (d(onlyOpenFolders)) ? onlyOpenFolders : false;
    _rightClick = d(_rightClick) ? _rightClick : false;

    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var resource = DataEngine.cannedResources.getResource(resourceId);

    lzm_chatDisplay.resourcesDisplay.SetPreview(resource);
    lzm_chatDisplay.selectedResource = resourceId;

    if (resource != null)
    {
        var parentFolder = DataEngine.cannedResources.getResource(resource.pid);
        var wasSelected = $('#'+sid+'resource-' + resourceId).hasClass('selected-resource-div');

        $('.resource-div').removeClass('selected-resource-div');
        $('.qrd-search-line').removeClass('selected-table-line');
        $('.qrd-recently-line').removeClass('selected-table-line');
        $('.resource-open-mark').removeClass('resource-open-mark-selected');
        $('.resource-icon-and-text').removeClass('resource-icon-and-text-selected');

        lzm_chatDisplay.resourcesDisplay.highlightSearchResults(DataEngine.cannedResources.CacheResourceList, false);

        $('#'+sid+'resource-' + resourceId).addClass('selected-resource-div');

        $('#qrd-'+sid+'search-line-' + resourceId).addClass('selected-table-line');
        $('#qrd-'+sid+'recently-line-' + resourceId).addClass('selected-table-line');
        $('#'+sid+'resource-' + resourceId + '-open-mark').addClass('resource-open-mark-selected');
        $('#'+sid+'resource-' + resourceId + '-icon-and-text').addClass('resource-icon-and-text-selected');
        $('.qrd-change-buttons').addClass('ui-disabled');

        switch (parseInt(resource.ty))
        {
            case 0:
                if(!_rightClick)
                    KnowledgebaseUI.OpenOrCloseFolder(resourceId, onlyOpenFolders, wasSelected, _context);
                if (resourceId != '1' && lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource))
                    $('#add-qrd').removeClass('ui-disabled');

                if (resourceId != '1' && lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource)) {
                    $('#add-or-edit-qrd').removeClass('ui-disabled');
                }
                $('#add-qrd-attachment').addClass('ui-disabled');
                break;
            case 1:
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                $('#view-qrd').removeClass('ui-disabled');

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', resource)) {
                    $('#add-or-edit-qrd').removeClass('ui-disabled');
                }
                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').addClass('ui-disabled');
                break;
            case 2:
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#edit-qrd').removeClass('ui-disabled');
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }
                $('#view-qrd').removeClass('ui-disabled');

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').addClass('ui-disabled');
                break;

            default:

                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'edit', resource)) {
                    $('#show-qrd-settings').removeClass('ui-disabled');
                }
                if (lzm_commonPermissions.checkUserPermissions('', 'resources', 'delete', resource)) {
                    $('#delete-qrd').removeClass('ui-disabled');
                }

                $('#'+sid+'send-qrd-preview').removeClass('ui-disabled');

                $('#insert-qrd-preview').removeClass('ui-disabled');

                if (lzm_chatDisplay.resourcesDisplay.SelectedResourceTab == 0 && parentFolder != null && lzm_commonPermissions.checkUserPermissions('', 'resources', 'add', parentFolder))
                    $('#add-qrd').removeClass('ui-disabled');

                $('#add-qrd-attachment').removeClass('ui-disabled');
                break;
        }
    }
};

KnowledgebaseUI.GetResourceStorageHash = function(_resId){
    return md5(_resId).substr(0,7);
};

KnowledgebaseUI.Synchronize = function(){

    KnowledgebaseUI.HandleResourceClickEvents('1');

    var resourceIdList = lzm_commonStorage.loadValue('kb_list_' + DataEngine.myId,'');
    if(resourceIdList != null)
    {
        try
        {
            resourceIdList = LocalConfiguration.ParseKnowledgeBaseShortList(resourceIdList);
            for(var key in resourceIdList)
            {
                lzm_commonStorage.deleteKeyValuePair('kb_i_' + DataEngine.myId + resourceIdList[key]);
            }
        }
        catch(ex)
        {
            deblog(resourceIdList);
            deblog(ex);
        }
    }

    lzm_commonStorage.deleteKeyValuePair('kb_dut_' + DataEngine.myId);
    lzm_commonStorage.deleteKeyValuePair('kb_list_' + DataEngine.myId);

    KnowledgebaseUI.CacheValid = false;
    KnowledgebaseUI.IsSyncing = true;

    DataEngine.resourceIdList = [];
    lzm_chatDisplay.resourcesDisplay.LoadedResources = [];
    lzm_chatDisplay.resourcesDisplay.CacheResourceList = [];

    DataEngine.resourceLastEdited = 0;
    CommunicationEngine.qrdRequestTime = 1;
    DataEngine.cannedResources = new LzmResources();
    KnowledgebaseUI.CreateRootResource();

    //lzm_chatDisplay.resourcesDisplay.UpdateResources();
    lzm_chatDisplay.resourcesDisplay.UpdateKBInfo(0);
};

KnowledgebaseUI.RenderFolderArrow = function(_resourceId,_hasChildren){
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var markDiv = $('#'+sid+'resource-' + _resourceId + '-open-mark');

    if(!d(_hasChildren))
    {
        var folderDiv = $('#'+sid+'folder-' + _resourceId);
        _hasChildren = (folderDiv.html() != '');
    }

    if(_hasChildren)
    {
        if ($.inArray(_resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
            markDiv.html('<i class="fa fa-caret-right"></i>');
        else
            markDiv.html('<i class="fa fa-caret-down"></i>');
    }
    else
        markDiv.html('');
};

KnowledgebaseUI.OpenOrCloseFolder = function(resourceId, onlyOpenFolders, wasSelected, _context) {
    var sid = (KnowledgebaseUI.RunsInDialog()) ? 'd-' : '';
    var folderDiv = $('#'+sid+'folder-' + resourceId);

    KnowledgebaseUI.PopulateFolder(resourceId,sid,false,_context);

    if (folderDiv.html() != "")
    {
        var markDiv = $('#'+sid+'resource-' + resourceId + '-open-mark');
        if (folderDiv.css('display') == 'none')
        {
            folderDiv.css('display', 'block');
            markDiv.html('<i class="fa fa-caret-down"></i>');
            if ($.inArray(resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
            {
                lzm_chatDisplay.resourcesDisplay.openedResourcesFolder.push(resourceId);
            }
        }
        else if (!onlyOpenFolders)
        {
            if(!wasSelected)
                return;

            folderDiv.css('display', 'none');
            markDiv.html('<i class="fa fa-caret-right"></i>');
            var tmpOpenedFolder = [];
            for (var i=0; i<lzm_chatDisplay.resourcesDisplay.openedResourcesFolder.length; i++) {
                if (resourceId != lzm_chatDisplay.resourcesDisplay.openedResourcesFolder[i]) {
                    tmpOpenedFolder.push(lzm_chatDisplay.resourcesDisplay.openedResourcesFolder[i]);
                }
            }
            lzm_chatDisplay.resourcesDisplay.openedResourcesFolder = tmpOpenedFolder;
        }
    }
};

KnowledgebaseUI.PopulateFolder = function(_resourceId,_sid,_onlyExpanded,_context){

    _onlyExpanded = d(_onlyExpanded) ? _onlyExpanded : false;

    if (_onlyExpanded && $.inArray(_resourceId, lzm_chatDisplay.resourcesDisplay.openedResourcesFolder) == -1)
        return;

    if(d(DataEngine.cannedResources.objects[_resourceId]))
    {
        var res,childNodes = KnowledgebaseUI.GetChildNodes(_resourceId);
        if(childNodes.length)
        {
            $('#'+_sid+'folder-' + _resourceId).html('');
            for(var key in childNodes)
            {
                res = childNodes[key];
                res.ra = lzm_chatDisplay.resourcesDisplay.CalculateRank(res,0);
                var resourceHtml = lzm_chatDisplay.resourcesDisplay.GetResourceHTML(res, _context, KnowledgebaseUI.RunsInDialog());
                $('#'+_sid+'folder-' + res.pid).append(resourceHtml);
            }
        }
    }
};

KnowledgebaseUI.ReplaceTitle = function(_resource){
    _resource.ti = _resource.ti
        .replace(/%%_Files_%%/, t('Files'))
        .replace(/%%_External_%%/, t('External'))
        .replace(/%%_Internal_%%/, t('Internal'));
    return _resource;
};

KnowledgebaseUI.GetChildNodes = function(_resourceId){

    var list=[],tlist = [];

    if(lzm_chatDisplay.resourcesDisplay.CacheResourceList != null)
        tlist = lzm_commonTools.GetElementByProperty(lzm_chatDisplay.resourcesDisplay.CacheResourceList,'pid',_resourceId);

    var parent = (_resourceId=='1') ? KnowledgebaseUI.GetRootResource() : lzm_commonTools.GetElementByProperty(lzm_chatDisplay.resourcesDisplay.CacheResourceList,'rid',_resourceId)[0];
    for (var i=0; i<tlist.length; i++) {

        var res = tlist[i];
        if (lzm_commonPermissions.checkUserResourceReadPermission(lzm_chatDisplay.myId, res, parent))
            list.push(res);
    }
    return list;
};

KnowledgebaseUI.GetRootResource = function(){
    return {
        di: "0",
        ed: "0",
        eid: "0000000",
        oid: "0000000",
        pid: "0",
        ra: "0",
        rid: "1",
        si: "6",
        t: "",
        text: t('Public'),
        ti: t('Public'),
        ty: "0"
    };
};

KnowledgebaseUI.CreateRootResource = function(){
    DataEngine.cannedResources.setResource(KnowledgebaseUI.GetRootResource());
};

KnowledgebaseUI.ChatKBAutoSearch = function(_instant){

    var i,previewHeight,shortcut,typingNow = lzm_chatTimeStamp.getServerTimeString(null, false, 1);
    $('#chat-qrd-preview').html('');
    if (typingNow - lastTypingEvent > 450 || _instant)
    {
        var editorContents = $.trim(grabEditorContents('plaintext').replace(/<.*?>/g, ''));
        if (editorContents.length > 1)
        {
            var frequentlyUsedResources = DataEngine.cannedResources.GetResourceList('usage_counter', {ty: '1,2,3,4', t: editorContents, text: editorContents, ti: editorContents, s: editorContents});
            var maxIterate = Math.min(10, frequentlyUsedResources.length), furHtml = '';

            if ($('#chat-progress').height() > 200 && frequentlyUsedResources.length > 0)
            {
                furHtml += '<table style="width:100%">';
                for (i=0; i<maxIterate; i++)
                {
                    var resourceText = '<span>' + lzm_commonTools.htmlEntities(frequentlyUsedResources[i].text.replace(/<.*?>/g, '')) + '</span>';

                    if(frequentlyUsedResources[i].ty == '3')
                        resourceText = '<span>' + lzm_commonTools.htmlEntities(frequentlyUsedResources[i].ti.replace(/<.*?>/g, '')) + '</span>';

                    if(d(frequentlyUsedResources[i].s))
                    {
                        shortcut = (frequentlyUsedResources[i].s.length) ? '<span class="editor-preview-shortcut" id="editor-preview-shortcut-' + frequentlyUsedResources[i].rid +'">/' + frequentlyUsedResources[i].s + '</span>' : '';
                        resourceText = '<td class="editor-preview-cell"><div class="editor-preview-inner">' + shortcut + resourceText + '</div></td>';

                        if (editorContents.indexOf('/') == 0 && ('/' + frequentlyUsedResources[i].s.toLowerCase()).indexOf(editorContents.toLowerCase()) == 0)
                            KnowledgebaseUI.ShortCutResources.push({id: frequentlyUsedResources[i].rid, complete: false});
                    }
                    furHtml += '<tr class="lzm-unselectable" style="cursor: pointer;" onclick="useEditorQrdPreview(\'' + frequentlyUsedResources[i].rid + '\');">' + resourceText + '</tr>';
                }
                furHtml += '</table>';

                $('#chat-qrd-preview').html(furHtml);
                lzm_chatDisplay.RenderWindowLayout(true);

                previewHeight = $('#chat-qrd-preview').height();
                $('#chat-progress').css({'bottom': (137 + previewHeight + ChatEditorClass.ExpandChatInputOffset) + 'px'});
                chatScrollDown(1);

                $('.editor-preview-inner').css({'max-width': ($('#chat-qrd-preview').width() - $('.editor-preview-shortcut').width() - 14)+'px'});

                for (i=0; i<KnowledgebaseUI.ShortCutResources.length; i++)
                {
                    var resource = DataEngine.cannedResources.getResource(KnowledgebaseUI.ShortCutResources[i].id);
                    KnowledgebaseUI.ShortCutResources[i].complete = (resource != null && '/' + resource.s == editorContents);

                }
                KnowledgebaseUI.QuickSearchReady = true;
            }
            else
            {
                KnowledgebaseUI.ShortCutResources = [];
                KnowledgebaseUI.QuickSearchReady = true;
            }
        }
        else
        {
            KnowledgebaseUI.ShortCutResources = [];
            KnowledgebaseUI.QuickSearchReady = true;
        }
    }
};

KnowledgebaseUI.InsertIntoTicket = function(ticketId){
    var resource = DataEngine.cannedResources.getResource(lzm_chatDisplay.selectedResource);
    if (resource != null)
    {
        DataEngine.cannedResources.riseUsageCounter(lzm_chatDisplay.selectedResource);

        TaskBarManager.RemoveActiveWindow();
        TaskBarManager.GetWindow(ticketId + '_reply').Maximize();

        var replyText = '';
        switch(resource.ty) {
            case '1':
                replyText += resource.text
                    .replace(/^<p>/gi,'').replace(/^<div>/gi,'')
                    .replace(/<p>/gi,'<br>').replace(/<div>/gi,'<br>')
                    .replace(/<br>/gi,'\n').replace(/<br \/>/gi, '\n');
                if (replyText.indexOf('openLink') != -1) {
                    replyText = replyText.replace(/<a.*openLink\('(.*?)'\).*>(.*?)<\/a>/gi, '$2 ($1)');
                } else {
                    replyText = replyText.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, '$2 ($1)');
                }
                replyText = replyText.replace(/<.*?>/g, '').replace(/&nbsp;/gi, ' ')
                    .replace(/&.*?;/g, '');
                break;
            case '2':
                replyText += resource.ti + ':\n' + resource.text;
                break;
            default:
                var urlFileName = encodeURIComponent(resource.ti.replace(/ /g, '+'));
                var fileId = resource.text.split('_')[1];
                var urlParts = lzm_commonTools.getUrlParts(CommunicationEngine.chosenProfile.server_protocol + CommunicationEngine.chosenProfile.server_url, 0);
                var thisServer = ((urlParts.protocol == 'http://' && urlParts.port == 80) || (urlParts.protocol == 'https://' && urlParts.port == 443)) ?
                    urlParts.protocol + urlParts.urlBase + urlParts.urlRest : urlParts.protocol + urlParts.urlBase + ':' + urlParts.protocol + urlParts.urlRest;
                replyText += thisServer + '/getfile.php?';
                replyText += 'file=' + urlFileName + '&id=' + fileId;
        }

        insertAtCursor('ticket-reply-input', replyText);
        $('#ticket-reply-input-resource').val(resource.rid);
        $('#ticket-reply-input').change();

        if (resource.ty.toString() == '1' && (!resource.p || resource.p=='0'))
            $('#ticket-reply-input-save').removeClass('ui-disabled');
        else
            $('#ticket-reply-input-save').addClass('ui-disabled');
    }
};

KnowledgebaseUI.InsertIntoChat = function(){
    if(TaskBarManager.GetActiveWindow() != null)
    {
        var sysId = TaskBarManager.GetActiveWindow().Tag;
        ChatManager.SaveEditorInput();
        removeEditor();
        lzm_chatDisplay.resourcesDisplay.CreateKBTreeDialog(null, sysId);
    }
};




