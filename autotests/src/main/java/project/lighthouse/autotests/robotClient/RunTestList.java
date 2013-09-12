
package project.lighthouse.autotests.robotClient;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlType;


/**
 * <p>Java class for runTestList complex type.
 * <p/>
 * <p>The following schema fragment specifies the expected content contained within this class.
 * <p/>
 * <pre>
 * &lt;complexType name="runTestList">
 *   &lt;complexContent>
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType">
 *       &lt;sequence>
 *         &lt;element name="cashIp" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *         &lt;element name="testList" type="{http://www.w3.org/2001/XMLSchema}string" minOccurs="0"/>
 *       &lt;/sequence>
 *     &lt;/restriction>
 *   &lt;/complexContent>
 * &lt;/complexType>
 * </pre>
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "runTestList", propOrder = {
        "cashIp",
        "testList"
})
public class RunTestList {

    protected String cashIp;
    protected String testList;

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
     * Gets the value of the testList property.
     *
     * @return possible object is
     *         {@link String }
     */
    public String getTestList() {
        return testList;
    }

    /**
     * Sets the value of the testList property.
     *
     * @param value allowed object is
     *              {@link String }
     */
    public void setTestList(String value) {
        this.testList = value;
    }

}
