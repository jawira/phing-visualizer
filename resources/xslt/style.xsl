<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" encoding="UTF-8" indent="no"/>

<xsl:template match="/project">
<xsl:variable name="name" select="@name"/>
title <xsl:value-of select="$name"/>
hide stereotype
skinparam Arrow {
Color #555555
FontColor #555555
}
skinparam UseCase {
BackgroundColor #FFFFCC
BorderColor #555555
}</xsl:template>

</xsl:stylesheet>
