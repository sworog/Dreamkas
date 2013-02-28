<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" mayak_application "
        >
        <div
            mayak_desktop="true"
            mayak_application="true"
            mayak_application_api="{ @mayak_application_api }"
            >
            <xsl:apply-templates />
        </div>
    </xsl:template>
    
</xsl:stylesheet>