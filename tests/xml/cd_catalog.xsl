<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/">
        <html>
            <body>
                <h2>My CD Collection</h2>
                <table border="1">
                    <tr bgcolor="#9acd32">
                        <th style="text-align:left">Title</th>
                        <th style="text-align:left">Artist</th>
                    </tr>
                    <xsl:for-each select="catalog/CD">
                        <tr>
                            <td><xsl:value-of select="TITLE"/></td>
                            <td><xsl:value-of select="ARTIST"/></td>
                        </tr>
                    </xsl:for-each>
                    <xsl:if test="/CD">
                    <tr>
                        <td><xsl:value-of select="CD/TITLE"/></td>
                        <td><xsl:value-of select="CD/ARTIST"/></td>
                    </tr>
                    </xsl:if>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>