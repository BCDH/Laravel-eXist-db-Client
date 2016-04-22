<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <xsl:for-each select="catalog/CD">
            <tr><td><xsl:value-of select="TITLE"/></td><td><xsl:value-of select="ARTIST"/></td></tr>
        </xsl:for-each>
        <xsl:if test="/CD">
        <tr><td><xsl:value-of select="CD/TITLE"/></td><td><xsl:value-of select="CD/ARTIST"/></td></tr>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>