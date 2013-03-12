<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" * "
        mode="lh_link_close"
        >
        
        <svg
            xmlns='http://www.w3.org/2000/svg'
            version="1.1"
            viewBox="0 0 17 17"
            width="16"
            height="16"
            lh_link_close="true"
            >
            
            <circle
                cx='8'
                cy='8'
                r='8'
                fill='#2286e0'
            />
            
            <line
                x1='5'
                y1='5'
                x2='11'
                y2='11'
                stroke='white'
                stroke-width='2'
            />
            
            <line
                x1='5'
                y1='11'
                x2='11'
                y2='5'
                stroke='white'
                stroke-width='2'
            />
            
        </svg>
        
    </xsl:template>
    
</xsl:stylesheet>