<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:output
        method="html"
        omit-xml-declaration="yes"
        doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    />
    
    <xsl:variable
        name="lh_page_stylesheetDecl"
        select="/processing-instruction()[ name() = 'xml-stylesheet' ]"
    />
    
    <xsl:variable
        name="lh_page_stylesheet"
        select=" substring-before( substring-after( $lh_page_stylesheetDecl, 'href=&#34;' ), '&#34;' ) "
    />
    
    <xsl:variable
        name="lh_page_stage"
        select=" substring-before( substring-after( $lh_page_stylesheet, '.stage=' ), '.' ) "
    />
    
    <xsl:template
        match=" lh_page "
        >
        <html jin_reset="true">
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
                
                <link href="{$lh_page_stylesheet}/../index.stage={$lh_page_stage}.css" rel="stylesheet" />
            </head>
            <body jin_reset="true">
                <xsl:apply-templates />
                <script src="{$lh_page_stylesheet}/../index.env=web.stage={$lh_page_stage}.js">//</script>
            </body>
        </html>
    </xsl:template>
    
</xsl:stylesheet>