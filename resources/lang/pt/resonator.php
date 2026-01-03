<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Caixa de entrada',
        'folders' => 'Pastas',
        'snippets' => 'Modelos',
        'spam_filters' => 'Filtros de spam',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Caixa de entrada',
        'sent' => 'Enviados',
        'archive' => 'Arquivo',
        'spam' => 'Spam',
        'trash' => 'Lixeira',
    ],

    // Labels
    'labels' => [
        'from' => 'De',
        'to' => 'Para',
        'cc' => 'CC',
        'bcc' => 'CCO',
        'subject' => 'Assunto',
        'body' => 'Corpo',
        'message' => 'Mensagem',
        'attachments' => 'Anexos',
        'snippet' => 'Modelo',
        'folder' => 'Pasta',
        'owner' => 'Proprietário',
        'date' => 'Data',
        'name' => 'Nome',
        'email' => 'E-mail',
        'phone' => 'Telefone',
        'company' => 'Empresa',
        'reason' => 'Motivo',
        'shortcut' => 'Atalho',
        'icon' => 'Ícone',
        'color' => 'Cor',
        'sort_order' => 'Ordem',
        'active' => 'Ativo',
        'system' => 'Sistema',
        'custom' => 'Personalizado',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Sincronizar e-mails',
        'syncing' => 'Sincronizando...',
        'compose' => 'Redigir',
        'reply' => 'Responder',
        'send' => 'Enviar',
        'archive' => 'Arquivar',
        'move_to_trash' => 'Mover para lixeira',
        'move_to_spam' => 'Marcar como spam',
        'move_to_folder' => 'Mover para pasta',
        'go_to_folder' => 'Ir para...',
        'mark_read' => 'Marcar como lido',
        'mark_unread' => 'Marcar como não lido',
        'toggle_star' => 'Alternar estrela',
        'change_owner' => 'Alterar proprietário',
        'create' => 'Criar',
        'edit' => 'Editar',
        'delete' => 'Excluir',
        'save' => 'Salvar',
        'cancel' => 'Cancelar',
    ],

    // Filters
    'filters' => [
        'folder' => 'Pasta',
        'read_status' => 'Status de leitura',
        'unread_only' => 'Apenas não lidos',
        'read_only' => 'Apenas lidos',
        'starred_only' => 'Apenas com estrela',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced novos e-mails sincronizados. :skipped já sincronizados. :spam movidos para spam.',
        'sync_error' => 'Erro ao sincronizar: :error',
        'email_sent' => 'E-mail enviado com sucesso',
        'email_send_error' => 'Falha ao enviar e-mail: :error',
        'moved_to_folder' => 'Movido para :folder',
        'archived' => 'Arquivado com sucesso',
        'moved_to_trash' => 'Movido para lixeira',
        'moved_to_spam' => 'Marcado como spam',
        'marked_read' => 'Marcado como lido',
        'marked_unread' => 'Marcado como não lido',
        'owner_changed' => 'Proprietário alterado',
        'added_to_spam_list' => 'Adicionado à lista de spam',
        'removed_from_spam_list' => 'Removido da lista de spam',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Excluir?',
        'delete_description' => 'Tem certeza de que deseja excluir isto?',
        'spam_title' => 'Marcar como spam?',
        'spam_description' => 'A mensagem será movida para spam e o remetente será adicionado à lista de filtros de spam.',
        'trash_title' => 'Mover para lixeira?',
        'trash_description' => 'Tem certeza de que deseja mover isto para a lixeira?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Selecionar destinatários...',
        'enter_subject' => 'Digite o assunto...',
        'enter_message' => 'Digite a mensagem...',
        'select_snippet' => 'Selecionar modelo...',
        'select_folder' => 'Selecionar pasta...',
        'enter_email' => 'Digite o e-mail...',
        'enter_reason' => 'Digite o motivo do bloqueio...',
        'enter_name' => 'Digite o nome...',
        'enter_shortcut' => 'Digite o atalho...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Atalho opcional para inserir rapidamente este modelo',
        'slug' => 'Identificador único para a pasta',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count mensagem|:count mensagens',
        'inbound' => 'Recebido',
        'outbound' => 'Enviado',
        'attachments' => 'Anexos',
        'download' => 'Baixar',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Você é um detector de spam para uma caixa de entrada de e-mail. Classifique um e-mail como spam se for:
- Newsletters ou resumos semanais
- Marketing ou publicidade
- Mensagens automatizadas de empresas
- Envios em massa não endereçados pessoalmente
- Ofertas ou campanhas
- Notificações de sistema de serviços (confirmações, notificações)

NÃO é spam se for:
- Uma consulta pessoal
- Uma pergunta de uma pessoa física ou organização
- Uma resposta a uma conversa anterior
- Uma consulta sobre preço ou disponibilidade
- Comunicação direta
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extraia as informações de contato do seguinte conteúdo de e-mail. Procure o nome do remetente, número de telefone e nome da empresa.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Atenciosamente',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Sem mensagens',
        'inbox_description' => 'Sua caixa de entrada está vazia.',
        'folder' => 'Nenhuma mensagem nesta pasta',
    ],

    // Misc
    'misc' => [
        'unread' => 'não lido',
        'starred' => 'Com estrela',
        'handled_by' => 'Tratado por',
        'select_all' => 'Selecionar tudo',
    ],
];
