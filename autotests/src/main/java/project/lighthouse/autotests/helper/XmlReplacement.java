package project.lighthouse.autotests.helper;

import org.apache.commons.io.FileUtils;
import org.w3c.dom.Document;
import org.xml.sax.SAXException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;
import java.io.File;
import java.io.IOException;
import java.io.StringWriter;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * The class is used for xml replacement/create purposes
 * It's deprecated. Use {@link project.lighthouse.autotests.xml.PurchaseXmlBuilder}
 */
@Deprecated
public class XmlReplacement {

    private File file;

    public XmlReplacement(File file) {
        this.file = file;
    }

    private String xmlToString() throws IOException, ParserConfigurationException, TransformerException, SAXException {
        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        DocumentBuilder builder = factory.newDocumentBuilder();
        Document doc = builder.parse(file);
        StringWriter stringWriter = new StringWriter();
        Transformer transformer = TransformerFactory.newInstance().newTransformer();
        transformer.transform(new DOMSource(doc), new StreamResult(stringWriter));
        return stringWriter.toString();
    }

    private String replace(String xml, String replaceString) throws IOException {
        StringBuffer resultString = new StringBuffer();
        Pattern regex = Pattern.compile("(datePattern)");
        Matcher regexMatcher = regex.matcher(xml);
        while (regexMatcher.find()) {
            regexMatcher.appendReplacement(resultString, replaceString);
        }
        regexMatcher.appendTail(resultString);
        return resultString.toString();
    }

    public void createFile(String replacementString, File file) throws IOException, TransformerException, ParserConfigurationException, SAXException {
        String xmlString = replace(xmlToString(), replacementString);
        FileUtils.writeStringToFile(file, xmlString);
    }
}
