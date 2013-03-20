<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:output method="html" />

<xsl:variable
    name="doc_page_stylesheetDecl"
    select="/processing-instruction()[ name() = 'xml-stylesheet' ]"
/>

<xsl:variable
    name="doc_page_stylesheet"
    select=" substring-before( substring-after( $doc_page_stylesheetDecl, 'href=&#34;' ), '&#34;' ) "
/>

<xsl:variable
    name="doc_page_stage"
    select=" substring-before( substring-after( $doc_page_stylesheet, '.stage=' ), '.' ) "
/>

<xsl:template match=" doc_list ">
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
            <script src="../../doc/-mix/index.env=web.stage=release.js">//</script>

        </head>
        <body wc_reset="true">
            
            <wc_desktop>
                
                <xsl:apply-templates select=" . " mode="doc_list_links" />
                
                <xsl:apply-templates select=" * " />
                
            </wc_desktop>
            
        </body>
    </html>
</xsl:template>

<xsl:template match=" doc_list / doc_root ">
    <wc_spacer>
        <wc_paper>
            <wc_article id="{ @doc_link }">
                <wc_article_title>
                    <h1 wc_reset="true">
                        <a href="{ @doc_link }" wc_reset="true">
                            <xsl:value-of select=" @doc_title " />
                        </a>
                    </h1>
                </wc_article_title>
                <wc_article_content>
                    <xsl:apply-templates select=" . " mode="doc_root_content" />
                </wc_article_content>
            </wc_article>
        </wc_paper>
    </wc_spacer>
</xsl:template>

<xsl:template match=" doc_list " mode="doc_list_links">
    <wc_sidebar>
        <wc_sidebar_panel wc_sidebar_edge="left">
            <wc_vmenu_root>
                <a wc_reset="true" href="../-mix/index.stage=release.doc.xml">
                    <wc_vmenu_leaf>
                        <xsl:value-of select=" 'RELEASE' " />
                    </wc_vmenu_leaf>
                </a>
                <a wc_reset="true" href="../-mix/index.stage=dev.doc.xml">
                    <wc_vmenu_leaf>
                        <xsl:value-of select=" 'DEV' " />
                    </wc_vmenu_leaf>
                </a>
                <xsl:apply-templates select=" doc_root " mode="doc_list_links">
                    <xsl:sort select="@doc_title" />
                </xsl:apply-templates>
            </wc_vmenu_root>
        </wc_sidebar_panel>
    </wc_sidebar>
</xsl:template>

<xsl:template match=" doc_list / doc_root " mode="doc_list_links">
    <a href="#{ @doc_link }" wc_reset="true">
        <wc_vmenu_leaf>
            <xsl:value-of select=" @doc_title " />
        </wc_vmenu_leaf>
    </a>
</xsl:template>

</xsl:stylesheet>
