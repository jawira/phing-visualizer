<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="text" version="2.0" encoding="UTF-8" indent="no"/>
  <xsl:param name="color"/>
  <xsl:template match="/project">
    <xsl:for-each select="./target">
      <xsl:text>(</xsl:text>
      <xsl:value-of select="@name"/>
      <xsl:text>)</xsl:text>
      <xsl:text>&#10;</xsl:text>
    </xsl:for-each>
  </xsl:template>
</xsl:stylesheet>
