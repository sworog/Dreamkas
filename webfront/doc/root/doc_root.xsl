<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:output method="html" />

<xsl:template match=" doc_root ">
    <html wc_reset="true">
        <head>
        
            <title>
                <xsl:value-of select=" @doc_title " />
            </title>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
            
            <link href="{$doc_page_stylesheet}/../index.stage={$doc_page_stage}.css" rel="stylesheet" />
            <script src="{$doc_page_stylesheet}/../index.env=web.stage={$doc_page_stage}.js">//</script>

            <link href="../../doc/-mix/index.stage=release.css" rel="stylesheet" />
            <script>
                setTimeout( function( ){
                    var script= document.createElement( 'script' )
                    script.src= '../../doc/-mix/index.env=web.stage=release.js'
                    document.head.appendChild( script )
                }, 500 )
            </script>
            
        </head>
        <body wc_reset="true">
            <wc_desktop>
                
                <xsl:apply-templates select=" document( '../-mix/index.stage=dev.doc.xml', . ) / doc_list " mode="doc_list_links" />
                
                <wc_spacer>
                    <wc_paper>
                        <wc_article>
                            <wc_article_title>
                                <h1 wc_reset="true">
                                    <xsl:value-of select=" @doc_title " />
                                </h1>
                            </wc_article_title>
                            <wc_article_content>
                                <xsl:apply-templates />
                            </wc_article_content>
                        </wc_article>
                    </wc_paper>
                </wc_spacer>
                
            </wc_desktop>
        </body>
    </html>
</xsl:template>

<xsl:template match=" doc_root " mode="doc_root_content">
    <xsl:apply-templates select=" document( @doc_link, . ) / * / * " />
</xsl:template>

<xsl:template match=" doc_root[ * ] " mode="doc_root_content">
    <xsl:apply-templates select=" * " />
</xsl:template>

<xsl:template match=" doc_root // * ">
    <xsl:copy>
        <xsl:copy-of select=" @* " />
        <xsl:apply-templates select=" node() " />
    </xsl:copy>
</xsl:template>

</xsl:stylesheet>
