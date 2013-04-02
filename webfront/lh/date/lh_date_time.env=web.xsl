<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
            match=" node() "
            mode="lh_date_time_view"
            >

        <xsl:value-of select=" substring( ., 9, 2 ) " />
        <xsl:text>.</xsl:text>
        <xsl:value-of select=" substring( ., 6, 2 ) " />
        <xsl:text>.</xsl:text>
        <xsl:value-of select=" substring( ., 1, 4 ) " />
        <xsl:text> </xsl:text>
        <xsl:value-of select=" substring( ., 12, 2 ) " />
        <xsl:text>:</xsl:text>
        <xsl:value-of select=" substring( ., 15, 2 ) " />

    </xsl:template>
    
</xsl:stylesheet>