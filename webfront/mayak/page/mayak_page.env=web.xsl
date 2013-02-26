<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:output
        method="html"
        omit-xml-declaration="yes"
        doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    />
    
    <xsl:variable
        name="mayak_page_stylesheet"
        select="/processing-instruction()[ name() = 'xml-stylesheet' ]"
    />
    
    <xsl:variable
        name="mayak_page_stage"
        select=" substring-before( substring-after( $mayak_page_stylesheet, '.stage=' ), '.' ) "
    />
    
    <xsl:template
        match=" mayak_page "
        >
        <html jin_reset="true">
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
                
                <link href="../-mix/index.stage={$mayak_page_stage}.css" rel="stylesheet" />
                <script src="../-mix/index.env=web.stage={$mayak_page_stage}.js">//</script>
            </head>
            <body jin_reset="true">
                <mayak_desktop>
                    <xsl:apply-templates />
                </mayak_desktop>
            </body>
        </html>
    </xsl:template>
    
</xsl:stylesheet>