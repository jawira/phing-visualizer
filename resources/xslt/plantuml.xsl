<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:str="http://exslt.org/strings"
                extension-element-prefixes="str">

<xsl:output method="text" encoding="UTF-8" indent="no"/>
<xsl:template match="/project">@startuml
<xsl:if test="@name">
title <xsl:value-of select="@name"/>
</xsl:if>
skinparam arrowFontColor Grey

<!--Printing all targets first-->
<xsl:for-each select="./target">(<xsl:value-of select="@name"/>)
</xsl:for-each>

<!--Printing dependencies and calls-->
<xsl:for-each select="./target">
<xsl:variable name="current-target" select="@name"/>
<xsl:for-each select=".//phingcall | .//foreach | .//runtarget">
(<xsl:value-of select="$current-target"/>) --> (<xsl:value-of select="@target"/>) : call:<xsl:value-of select="position()"/>
</xsl:for-each>
<xsl:if test="@depends">
<xsl:call-template name="print-depends">
<xsl:with-param name="from" select="@name"/>
<xsl:with-param name="depends" select="@depends"/>
</xsl:call-template>
</xsl:if>
</xsl:for-each>
@enduml
</xsl:template>

<!--Printing depends-->
<xsl:template name="print-depends">
<xsl:param name="from"/>
<xsl:param name="depends"/>
<xsl:variable name="targets" select="str:split($depends, ',')"/>
<xsl:for-each select="$targets">
(<xsl:value-of select="$from"/>) --> (<xsl:value-of select="normalize-space(text())"/>) : depend:<xsl:value-of select="position()"/>
</xsl:for-each>
</xsl:template>

</xsl:stylesheet>
