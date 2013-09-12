
package project.lighthouse.autotests.robotClient;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlType;


/**
 * <p>Java class for getTestList complex type.
 * <p/>
 * <p>The following schema fragment specifies the expected content contained within this class.
 * <p/>
 * <pre>
 * &lt;complexType name="getTestList">
 *   &lt;complexContent>
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType">
 *       &lt;sequence>
 *         &lt;element name="testTags" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *       &lt;/sequence>
 *     &lt;/restriction>
 *   &lt;/complexContent>
 * &lt;/complexType>
 * </pre>
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "getTestList", propOrder = {
        "testTags"
})
public class GetTestList {

    protected String testTags;

    /**
     * Gets the value of the testTags property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getTestTags() {
        return testTags;
    }

    /**
     * Sets the value of the testTags property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setTestTags(String value) {
        this.testTags = value;
    }

}
