<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:variable name="missingData" select="'red'"/>
    <xsl:template match="/">
        <html>
            <head>
                <meta charset="UTF-8"/>
                <link rel="stylesheet" href="projects.css"/>
            </head>
            <body>
                Pierwszym projektem w dokumencie XML jest <b><xsl:value-of select="/projects/project[1]/title"/></b><br/>
                Ostatnim projektem w dokumencie XML jest <b><xsl:value-of select="/projects/project[last()]/title"/></b><br/>
                Liczba projektów w dokumencie:<b><xsl:value-of select='count(/projects/project)'/></b><br/>
                Samodzielnie wykonanych projektów: <b><xsl:value-of select="count(/projects/project[@contributors='1'])"/></b><br/>
                
                Wszystkie projekty są udostępniane na zasadach <b><xsl:value-of select='name(/projects/project/@openSource)'/></b><br/>
                <hr/>
                Ostatnia data modyfikacji dokumentu transformacji:
                <xsl:value-of select="concat('03-','11-','19')"/>
                <br/>
                <xsl:apply-templates/>     
            </body>
        </html>
    </xsl:template>

    <xsl:template match="/projects">
        <xsl:for-each select="project">
            <xsl:sort select="releaseDate"/>
            <xsl:number value="position()-1" format="1" />
            <xsl:apply-templates select="."/> 
            <xsl:apply-templates select="@content"/>
            <xsl:apply-templates select="@contributors"/>
            <hr/>
        </xsl:for-each>
    </xsl:template>

    <xsl:template name="simpleTemplate">     
        <strong><xsl:value-of select="."/></strong>
    </xsl:template>

    <xsl:template match="title">
        <xsl:call-template name="simpleTemplate"/>
    </xsl:template>

    <xsl:template match="releaseDate">
        <xsl:call-template name="simpleTemplate"/>
    </xsl:template>

    <xsl:template match="@contributors">
        <p class="info">
            <xsl:if test=". > 1">
                Robiłem ten projekt w zespole
            </xsl:if>
        </p>
    </xsl:template>

    <xsl:template match="@content">
        <p><a href="{.}"><xsl:value-of select="."/></a></p>
    </xsl:template>

    <xsl:template match="description">
        <br/><xsl:value-of select="."/>
        <xsl:choose>
            <xsl:when test="not(../images)">
                <p style="color: {$missingData};">Brak zdjęć dla tego projektu</p>
            </xsl:when>
            <xsl:otherwise>
                <div class="images">
                    <xsl:apply-templates select="image"/>
                </div>
            </xsl:otherwise>
        </xsl:choose>
        <xsl:choose>
            <xsl:when test="not(../releaseDate)">
                <p style="color: {$missingData};">Projekt nie został jeszcze zakończony</p>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="releaseDate"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="image">
        <img src="./{@source}"/>
    </xsl:template>

    <xsl:template match="technologies">
        <h4><xsl:value-of select="name()"/></h4>
        <xsl:if test="count(technology) > 3">
            <p><strong>Wykorzystałem w tym projekcie ponad 3 różne technologie:</strong></p>
        </xsl:if>
        <ul>          
            <xsl:for-each select="technology">
                <li>
                    <xsl:number value="position()" format="#" />
                    <xsl:value-of select="."/>   
                </li>    
            </xsl:for-each>
        </ul>
    </xsl:template>

</xsl:stylesheet>