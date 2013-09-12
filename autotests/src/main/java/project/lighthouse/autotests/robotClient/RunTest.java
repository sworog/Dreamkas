
package project.lighthouse.autotests.robotClient;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlType;


/**
 * <p>Java class for runTest complex type.
 * <p/>
 * <p>The following schema fragment specifies the expected content contained within this class.
 * <p/>
 * <pre>
 * &lt;complexType name="runTest">
 *   &lt;complexContent>
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType">
 *       &lt;sequence>
 *         &lt;element name="cashIp" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *         &lt;element name="testName" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *       &lt;/sequence>
 *     &lt;/restriction>
 *   &lt;/complexContent>
 * &lt;/complexType>
 * </pre>
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "runTest", propOrder = {
        "cashIp",
        "testName"
})
public class RunTest {

    protected String cashIp;
    protected String testName;

    /**
     * Gets the value of the cashIp property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getCashIp() {
        return cashIp;
    }

    /**
     * Sets the value of the cashIp property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setCashIp(String value) {
        this.cashIp = value;
    }

    /**
     * Gets the value of the testName property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getTestName() {
        return testName;
    }

    /**
     * Sets the value of the testName property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setTestName(String value) {
        this.testName = value;
    }

}
