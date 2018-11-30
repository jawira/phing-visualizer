<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" encoding="UTF-8" indent="no"/>

<xsl:template match="/project">
<xsl:variable name="current-target" select="@name"/>
rectangle "<xsl:value-of select="$current-target"/>" {
<xsl:for-each select="./target">(<xsl:value-of select="@name"/>)
</xsl:for-each>}</xsl:template>

</xsl:stylesheet>
