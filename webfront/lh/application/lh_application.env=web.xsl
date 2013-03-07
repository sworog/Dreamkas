<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" lh_application "
        >
        <div
            lh_desktop="true"
            >
            <div
                lh_application="true"
                lh_application_api="{ @lh_application_api }"
                >
                <xsl:apply-templates />
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>