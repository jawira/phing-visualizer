<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="text" encoding="UTF-8" indent="no"/>

    <xsl:template match="/project">
        @startuml
        <xsl:if test="@name">
            title <xsl:value-of select="@name"/>
        </xsl:if>
        skinparam arrowFontColor Grey
        <xsl:for-each select="./target">
            (<xsl:value-of select="@name"/>)
        </xsl:for-each>
        <xsl:for-each select="./target">
            <xsl:variable name="current-target" select="@name"/>
            <xsl:for-each select=".//phingcall">
                (<xsl:value-of select="$current-target"/>) --> (<xsl:value-of select="@target"/>) : call:<xsl:value-of select="position()"/>
            </xsl:for-each>
        </xsl:for-each>
        @enduml
    </xsl:template>

</xsl:stylesheet>
