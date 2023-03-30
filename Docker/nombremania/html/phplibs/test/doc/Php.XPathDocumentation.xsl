<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns="http://www.w3.org/TR/xhtml1/strict">
<!-- author: Nigel Swinson nigelswinson@users.sourceforge.net -->
<!-- version: 1.0.1 -->

<xsl:template match="/">
	<h1>Conents</h1>

	<ul>
		<li><a href="#PublicList">Public Methods</a></li>
		<li><a href="#PrivateList">Private Methods</a></li>
		<li><a href="#PublicMethods">Public Methods Detail</a></li>
		<li><a href="#PrivateMethods">Private Methods Detail</a></li>
	</ul>
	
	<h1>Public Methods<a name="#PublicList"/></h1>

	<ul>
	<xsl:for-each select="//Function[not(starts-with(FunctionName, '_')) and not(Deprecate)]">
		<li><nobr>
			<span class="label" style="width:200">
				<a href="#{FunctionName}"><xsl:value-of select="FunctionName"/></a>
			</span> : 
			<span style="margin-left:200;margin-top:0;">
				<xsl:value-of select="ShortComment"/>
			</span>
			</nobr>
		</li>
	</xsl:for-each>
	</ul>

	<h2>Depreciated public methods</h2>
	<ul>
	<xsl:for-each select="//Function[not(starts-with(FunctionName, '_')) and Deprecate]">
		<li><nobr>
			<span class="label" style="width:200">
				<a href="#{FunctionName}"><xsl:value-of select="FunctionName"/></a>
			</span> : 
			<span style="margin-left:200;margin-top:0;">
				<xsl:value-of select="Deprecate"/>
			</span>
			</nobr>
		</li>
	</xsl:for-each>
	</ul>

	
	<h1>Private Methods<a name="#PrivateList"/></h1>

	<p>You really shouldn't be raking about in these functions, as you should only be
	using the public interface.  But if you need more control, then these are the
	internal functions that you can use if you want to get your hands really dirty.</p>

	<ul>
	<xsl:for-each select="//Function[starts-with(FunctionName, '_')]">
		<li>
			<nobr>
			<span class="label" style="width:330">
				<a href="#{FunctionName}"><xsl:value-of select="FunctionName"/></a>
			</span> : 
			<span style="margin-left:300;margin-top:0;">
				<xsl:value-of select="ShortComment"/>
			</span>
			</nobr>
		</li>
	</xsl:for-each>
	</ul>

	<h1>Public Method Detail<a name="#PublicMethods"/></h1>

	<xsl:apply-templates select="//Function[not(starts-with(FunctionName, '_'))]"/>
	
	<h1>Private Method Detail<a name="#PrivateMethods"/></h1>
	<xsl:apply-templates select="//Function[starts-with(FunctionName, '_')]"/>

</xsl:template>

<xsl:template match="//Function">
	<a name='#{FunctionName}'></a>

	<span class="header">
	 Method Details: <xsl:value-of select="FunctionName"/>
	</span>

	<hr/><br/>

	<span class="content">
		<p class="label"><xsl:value-of select="Prototype"/></p>
		<p class="description"><xsl:value-of select="ShortComment"/></p>

		<pre class="description"><xsl:value-of select="Comment"/></pre>

		<xsl:if test="./Deprecate">
			<p class="description"><xsl:value-of select="Deprecate"/></p>
		</xsl:if>

		<xsl:if test="./Parameters">
			<p class="label">Parameter:</p>
			<xsl:apply-templates select="Parameters"/>
		</xsl:if>

		<xsl:if test="./Return">
			<p class="label">Return Value:</p>
			<p class="description"><xsl:value-of select="Return"/></p>
		</xsl:if>

		<xsl:if test="./Author">
			<p class="label">Author:</p>
			<p class="description"><xsl:value-of select="Author"/></p>
		</xsl:if>

		<xsl:if test="./See">
			<p class="label">Similar Functions:</p>
			<p class="description"><xsl:value-of select="See"/></p>
		</xsl:if>

		<p class="label" style="float:left">Line:</p>
		<p class="description">Defined on line <xsl:value-of select="LineNumber"/></p>
	</span>
</xsl:template>

<xsl:template match="Param">
	<p class="description"><xsl:value-of select="."/></p>			
</xsl:template>

</xsl:stylesheet>