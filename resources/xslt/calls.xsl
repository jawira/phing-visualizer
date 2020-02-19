<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:str="http://exslt.org/strings"
                extension-element-prefixes="str">
  <xsl:output method="text" version="2.0" encoding="UTF-8" indent="no"/>
  <xsl:template match="/project">


    <!--Printing dependencies and calls-->
    <xsl:for-each select="./target">
      <xsl:if test="@depends">
        <xsl:call-template name="print-depends">
          <xsl:with-param name="from" select="@name"/>
          <xsl:with-param name="depends" select="@depends"/>
        </xsl:call-template>
      </xsl:if>
      <xsl:variable name="current-target" select="@name"/>
      <xsl:for-each select=".//phingcall | .//foreach | .//runtarget">
        <xsl:text>(</xsl:text>
        <xsl:value-of select="$current-target"/>
        <xsl:text>)</xsl:text>
        <xsl:text>-[#EC87C0]-></xsl:text>
        <xsl:text>(</xsl:text>
        <xsl:value-of select="@target"/>
        <xsl:text>)</xsl:text>
        <xsl:text> : call:</xsl:text>
        <xsl:value-of select="position()"/>
        <xsl:text>&#10;</xsl:text>
      </xsl:for-each>
    </xsl:for-each>
  </xsl:template>

  <!--Printing depends-->
  <xsl:template name="print-depends">
    <xsl:param name="from"/>
    <xsl:param name="depends"/>
    <xsl:variable name="targets" select="str:split($depends, ',')"/>
    <xsl:for-each select="$targets">
      <xsl:text>(</xsl:text>
      <xsl:value-of select="$from"/>
      <xsl:text>)-[#5D9CEC]->(</xsl:text>
      <xsl:value-of select="normalize-space(text())"/>
      <xsl:text>) : depend:</xsl:text>
      <xsl:value-of select="position()"/>
      <xsl:text>&#10;</xsl:text>
    </xsl:for-each>
  </xsl:template>

</xsl:stylesheet>
