<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="text" version="2.0" encoding="UTF-8" indent="no"/>
  <xsl:template match="/project">
    <xsl:text>@startuml</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:variable name="name" select="@name"/>
    <xsl:text>title </xsl:text>
    <xsl:value-of select="$name"/>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam ArrowFontColor Black</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam ArrowThickness 2</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam UseCaseBackgroundColor #FFFECC</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam UseCaseBorderColor #333333</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam UseCaseBorderThickness 2</xsl:text>
    <xsl:text>&#10;</xsl:text>
    <xsl:text>skinparam UseCaseFontColor Black</xsl:text>
    <xsl:text>&#10;</xsl:text>
  </xsl:template>
</xsl:stylesheet>
