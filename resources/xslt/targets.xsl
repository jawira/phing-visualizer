<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" encoding="UTF-8" indent="no"/>
<xsl:param name="color"/>
<xsl:template match="/project">
<xsl:variable name="current-target" select="@name"/>
skinparam UseCase {
BackgroundColor&lt;&lt; <xsl:value-of select="$current-target"/> &gt;&gt; <xsl:value-of select="$color"/>
}
<xsl:for-each select="./target">(<xsl:value-of select="@name"/>)&lt;&lt; <xsl:value-of select="$current-target"/> &gt;&gt;
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>
